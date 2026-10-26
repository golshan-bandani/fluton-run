<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // [difficulty, question, [CORRECT first, wrong, wrong, wrong], explanation]
        $rows = [
            // ---------- EASY (worlds 1-2) ----------
            [1, 'What is Fluton\'s main focus?',
                ['On-chain privacy', 'NFT marketplace', 'Gaming rewards', 'Social media'],
                'Fluton\'s docs open with "Say hello to on-chain privacy".'],
            [1, '"Zero MEV" means no front-running and no ___ attacks.',
                ['Sandwich', 'Phishing', 'DDoS', 'Replay'],
                'Zero MEV: no front-running, no sandwich attacks.'],
            [1, 'What does Fluton keep encrypted from creation to settlement?',
                ['Intents', 'Chat messages', 'Wallet nicknames', 'Website cookies'],
                'Encrypted intents give full privacy from creation to settlement.'],
            [1, 'Where can you read the Fluton documentation?',
                ['docs.fluton.io', 'wiki.fluton.org', 'fluton.dev/help', 'docs.google.com/fluton'],
                'The Fluton docs went live at docs.fluton.io.'],
            [1, 'What is Fluton\'s handle on X (Twitter)?',
                ['@FlutonIO', '@FlutonApp', '@Fluton_Labs', '@PrivateFluton'],
                'Follow @FlutonIO for announcements.'],
            [1, 'Which of these was a Fluton testnet feature?',
                ['Private swaps', 'NFT minting', 'Pizza staking', 'Meme launchpad'],
                'The testnet let users try encrypted intents, private swaps and cross-chain execution.'],
            [1, 'In the tokenized-assets teaser, what stays hidden?',
                ['Your positions', 'The token logo', 'The chain name', 'The website'],
                'No public positions, no strategy leaks.'],

            // ---------- MEDIUM (worlds 3-4) ----------
            [2, 'Which partner was named in the tokenized assets teaser?',
                ['Fhenix', 'Chainlink', 'Uniswap', 'Lido'],
                'Tokenized assets are coming to Fluton together with Fhenix.'],
            [2, 'What does Fluton\'s modular architecture let you do?',
                ['Build your own private apps', 'Mine Bitcoin', 'Print NFTs', 'Run a CEX'],
                'Modular architecture: build your own private apps.'],
            [2, '"Unified privacy" means one interface across every ___.',
                ['Chain', 'Phone brand', 'Language', 'Exchange fee'],
                'Unified privacy: one interface across every chain.'],
            [2, 'What does confidential settlement keep hidden?',
                ['Balances', 'Block numbers', 'Chain IDs', 'The logo'],
                'Confidential settlement: balances stay hidden.'],
            [2, 'On which date did the Fluton testnet close?',
                ['Feb 21, 2026', 'Jan 30, 2026', 'Mar 9, 2026', 'Mar 25, 2026'],
                'The testnet closed on Feb 21, 2026.'],
            [2, 'How many designs could each person submit in the mascot contest?',
                ['3', '1', '5', '10'],
                'Each participant could submit up to 3 designs.'],
            [2, 'Which email address is for integration partnerships?',
                ['contact@fluton.io', 'hello@fluton.com', 'team@fluton.xyz', 'dev@fluton.io'],
                'Partners are welcome at contact@fluton.io.'],

            // ---------- HARD (worlds 5-6) ----------
            [3, 'Which two technologies make up Fluton\'s "ultimate privacy engine"?',
                ['FHE + ZK', 'PoW + PoS', 'MPC + SGX', 'VDF + BLS'],
                'FHE + ZK: the ultimate privacy engine.'],
            [3, 'At what time (UTC) did the Fluton testnet close on Feb 21?',
                ['18:00', '00:00', '12:00', '23:59'],
                'The testnet closed on Feb 21 at 18:00 UTC.'],
            [3, 'How long did Early Role Purge #2 last?',
                ['48 hours', '24 hours', '7 days', '1 hour'],
                'Members had 48 hours to secure their Early role.'],
            [3, 'Who won 1st place in the Fluton mascot contest?',
                ['Kuznets', 'aliendrooo', 'KirA', 'Perseus'],
                '1st: Kuznets, 2nd: aliendrooo, 3rd: KirA.'],
            [3, 'How many voucher codes did the mascot contest winner get?',
                ['2', '1', '3', '5'],
                '1st place got the Encrypted + Fluton Creator roles and 2 voucher codes.'],
            [3, 'About how many Early role holders lost the role in Purge #2?',
                ['About 3/4', 'About 1/10', 'About 1/2', 'Nobody'],
                'Approximately 3/4 lost the role for being inactive during the purge.'],
            [3, 'When did the Fluton Docs go live?',
                ['Mar 25, 2026', 'Jan 30, 2026', 'Feb 21, 2026', 'Mar 2, 2026'],
                'The docs went live on Mar 25, 2026.'],
        ];

        foreach ($rows as [$diff, $q, $opts, $why]) {
            Question::updateOrCreate(
                ['question' => $q],
                ['difficulty' => $diff, 'options' => $opts, 'answer' => $opts[0], 'explanation' => $why]
            );
        }
    }
}
