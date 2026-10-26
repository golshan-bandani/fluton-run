<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameApiController extends Controller
{
    private const MAX_WORLD = 6;
    private const MAX_SCORE = 500000;   // sanity cap: anything above is rejected

    /** Create a player (returns a secret token) or log in with name + token. */
    public function register(Request $r): JsonResponse
    {
        $d = $r->validate([
            'name'  => ['required', 'string', 'min:3', 'max:16', 'regex:/^[A-Za-z0-9_.\-]+$/'],
            'token' => ['nullable', 'string', 'size:40'],
        ]);

        $player = $this->findByName($d['name']);

        if ($player) {
            if (!empty($d['token']) && hash_equals($player->token, $d['token']) && !$player->banned) {
                return response()->json($this->payload($player));
            }
            return response()->json(['message' => 'NAME TAKEN'], 409);
        }

        $player = Player::create(['name' => $d['name'], 'token' => Str::random(40)]);
        return response()->json($this->payload($player, true), 201);
    }

    /** Top 10 players. */
    public function leaderboard(): JsonResponse
    {
        $rows = Player::where('banned', false)
            ->where('best_score', '>', 0)
            ->orderByDesc('best_score')
            ->orderBy('id')
            ->limit(10)
            ->get(['name', 'best_score', 'cleared']);

        return response()->json($rows->values()->map(fn ($p, $i) => [
            'rank'    => $i + 1,
            'name'    => $p->name,
            'score'   => (int) $p->best_score,
            'cleared' => (int) $p->cleared,
        ]));
    }

    /** Save a run. If cleared_world is sent, world progress is updated too. */
    public function submitScore(Request $r): JsonResponse
    {
        $d = $r->validate([
            'name'          => ['required', 'string', 'max:16'],
            'token'         => ['required', 'string', 'size:40'],
            'score'         => ['required', 'integer', 'min:0', 'max:' . self::MAX_SCORE],
            'cleared_world' => ['nullable', 'integer', 'min:1', 'max:' . self::MAX_WORLD],
        ]);

        $p = $this->findByName($d['name']);
        if (!$p || $p->banned || !hash_equals($p->token, $d['token'])) {
            return response()->json(['message' => 'FORBIDDEN'], 403);
        }

        if (!empty($d['cleared_world'])) {
            // you can only clear a world you have already unlocked
            if ($d['cleared_world'] > min($p->cleared + 1, self::MAX_WORLD)) {
                return response()->json(['message' => 'WORLD LOCKED'], 422);
            }
            $p->cleared = max($p->cleared, $d['cleared_world']);
        }

        if ($d['score'] > $p->best_score) {
            $p->best_score = $d['score'];
        }
        $p->save();

        return response()->json($this->payload($p));
    }

    /** One random question (shuffled options, no answer). */
    public function quiz(Request $r): JsonResponse
    {
        $diff    = max(1, min(3, (int) $r->query('d', 1)));
        $exclude = collect(explode(',', (string) $r->query('exclude', '')))
            ->map(fn ($v) => (int) $v)->filter()->values()->all();

        $q = Question::where('difficulty', $diff)->whereNotIn('id', $exclude)->inRandomOrder()->first()
            ?? Question::where('difficulty', $diff)->inRandomOrder()->first();

        if (!$q) {
            return response()->json(['message' => 'NO QUESTIONS'], 404);
        }

        $opts = $q->options;
        shuffle($opts);

        return response()->json([
            'id'         => $q->id,
            'difficulty' => $q->difficulty,
            'question'   => $q->question,
            'options'    => $opts,
        ]);
    }

    /** Check an answer. */
    public function answer(Request $r): JsonResponse
    {
        $d = $r->validate([
            'id'     => ['required', 'integer'],
            'answer' => ['required', 'string', 'max:200'],
        ]);

        $q = Question::find($d['id']);
        if (!$q) {
            return response()->json(['message' => 'NOT FOUND'], 404);
        }

        return response()->json([
            'correct'     => hash_equals(trim($q->answer), trim($d['answer'])),
            'answer'      => $q->answer,
            'explanation' => $q->explanation,
        ]);
    }

    private function findByName(string $name): ?Player
    {
        return Player::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();
    }

    private function payload(Player $p, bool $withToken = false): array
    {
        $a = [
            'name'    => $p->name,
            'cleared' => (int) $p->cleared,
            'best'    => (int) $p->best_score,
            // position on the leaderboard (null until the player has a score)
            'rank'    => $p->best_score > 0
                ? Player::where('banned', false)->where('best_score', '>', $p->best_score)->count() + 1
                : null,
        ];
        if ($withToken) {
            $a['token'] = $p->token;
        }
        return $a;
    }
}
