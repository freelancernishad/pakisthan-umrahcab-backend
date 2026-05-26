<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        EmailTemplate::truncate();
        
        $categories = ['Welcome', 'OTP', 'Invoice', 'Payment', 'Marketing'];
        $totalPerCategory = 12;

        foreach ($categories as $cat) {
            for ($i = 1; $i <= $totalPerCategory; $i++) {
                $uniqueDesign = $this->generateTrueUniqueDesign($cat, $i);
                
                EmailTemplate::create([
                    'name' => "[$cat] " . $uniqueDesign['layout_name'] . " - Variation $i",
                    'subject' => $this->getUniqueSubject($cat, $i),
                    'content_json' => json_encode($uniqueDesign['json']),
                    'content_html' => $uniqueDesign['html'],
                ]);
            }
        }
    }

    private function generateTrueUniqueDesign($cat, $idx)
    {
        $colors = ['#4f46e5', '#0891b2', '#059669', '#dc2626', '#d97706', '#7c3aed', '#be123c', '#1e293b', '#111827', '#2563eb'];
        $mainColor = $colors[($idx + strlen($cat)) % count($colors)];
        
        $json = [
            'body' => [
                'backgroundColor' => ($idx % 3 == 0) ? '#f1f5f9' : (($idx % 3 == 1) ? '#ffffff' : '#111827'),
                'contentDefaultWidth' => '600px',
                'fontFamily' => ['label' => 'Inter', 'value' => 'Poppins, sans-serif'],
                'rows' => []
            ]
        ];
        $rows = &$json['body']['rows'];
        $isDark = $json['body']['backgroundColor'] == '#111827';
        $textColor = $isDark ? '#e2e8f0' : '#4b5563';
        $headingColor = $isDark ? '#ffffff' : '#111827';

        // Choose a Layout Pattern (1-12 patterns to ensure variety)
        $pattern = $idx; 

        // 1. Header Row (Varying per pattern)
        if ($pattern % 4 == 0) {
            $rows[] = $this->row1([$this->img('120x40', 'LOGO')], 'transparent', '20px');
        } elseif ($pattern % 4 == 1) {
            $rows[] = $this->row2([$this->img('100x30', 'LOGO')], [$this->text('RIGHT', 'View Online', $mainColor, '11px')], 'white');
        } else {
            $rows[] = $this->row1([$this->text('CENTER', strtoupper($cat) . ' | OFFICIAL', $mainColor, '10px', '20px')], 'transparent');
        }

        // 2. Hero / Intro (Varying Layouts)
        switch ($pattern) {
            case 1:case 6: // Split Image/Text
                $rows[] = $this->row2([$this->img('280x280', 'Hero')], [$this->heading($cat, $headingColor), $this->text('LEFT', 'Hi @{{name}}, start your journey.', $textColor)], 'white', '30px');
                break;
            case 2:case 7: // Dark Full Width Hero
                $rows[] = $this->row1([$this->heading($cat, '#fff', 'center', '40px'), $this->text('CENTER', 'EDITION #'.$idx, '#9ca3af', '12px')], '#1e293b', '50px');
                break;
            case 3:case 8: // Sidebar Highlight
                $rows[] = $this->rowSidebarText([$this->text('LEFT', 'IMPORTANT', $mainColor, '10px')], [$this->heading($cat . " Update", $headingColor)], 'white');
                break;
            default: // Centered Minimal
                $rows[] = $this->row1([$this->heading('ZILMONEY - ' . $cat, $mainColor, 'center')], 'white', '40px');
        }

        // 3. Category Logic (Unique formats for each category)
        $rows[] = $this->getComplexCategoryRow($cat, $idx, $mainColor, $isDark);

        // 4. Feature Blocks (Randomly added for variety)
        if ($idx % 5 == 0) {
            $rows[] = $this->row3([$this->text('CENTER', 'Fast')], [$this->text('CENTER', 'Safe')], [$this->text('CENTER', 'Easy')], '#f8fafc');
        }

        // 5. Footer (Varying structures)
        if ($idx % 2 == 0) {
            $rows[] = $this->row1([$this->text('CENTER', '© 2026 @{{company}}', $textColor, '11px'), ['type' => 'social']], 'transparent', '40px');
        } else {
            $rows[] = $this->row2([$this->text('LEFT', 'Contact Support', $mainColor)], [$this->text('RIGHT', 'Unsubscribe', '#9ca3af')], 'transparent', '20px');
        }

        return [
            'layout_name' => "Layout Style " . $pattern,
            'json' => $json,
            'html' => "<div style='background:{$mainColor}; color:#fff; padding:60px; text-align:center;'><h2>$cat Unique V7</h2><p>Pattern ID: $pattern</p></div>"
        ];
    }

    private function getComplexCategoryRow($cat, $idx, $color, $isDark)
    {
        $textColor = $isDark ? '#cbd5e1' : '#4b5563';
        switch ($cat) {
            case 'Invoice':
                return [
                    'cells' => [1],
                    'columns' => [[
                        'contents' => [
                            ['type' => 'text', 'values' => ['text' => "<div style='border:2px solid $color; border-radius:12px; padding:20px;'><h4 style='margin:0'>Total: $29.00</h4><p style='font-size:12px;'>Paid via Credit Card</p></div>"]]
                        ]
                    ]]
                ];
            case 'OTP':
                return $this->row1([['type' => 'text', 'values' => ['text' => "<div style='background:$color; color:#fff; padding:30px; border-radius:100px; text-align:center; font-size:32px; letter-spacing:10px;'>123456</div>"]]], 'transparent');
            case 'Marketing':
                return $this->row2([$this->img('200x200', 'DEAL')], [$this->heading('50% OFF', $color), $this->text('LEFT', 'Use code ZIL50', $textColor)], 'white');
            case 'Welcome':
                return $this->row3([$this->img('80x80', '1')], [$this->img('80x80', '2')], [$this->img('80x80', '3')], 'transparent');
            default:
                return $this->row1([$this->text('LEFT', 'Your account has been updated.', $textColor)], 'white');
        }
    }

    // --- MODULAR ROW BUILDERS ---
    private function row1($contents, $bg = 'white', $padding = '20px') {
        return ['cells' => [1], 'backgroundColor' => $bg, 'columns' => [['contents' => $contents, 'values' => ['padding' => $padding]]]];
    }
    private function row2($c1, $c2, $bg = 'white', $padding = '20px') {
        return ['cells' => [1, 1], 'backgroundColor' => $bg, 'columns' => [['contents' => $c1], ['contents' => $c2]]];
    }
    private function row3($c1, $c2, $c3, $bg = 'white') {
        return ['cells' => [1, 1, 1], 'backgroundColor' => $bg, 'columns' => [['contents' => $c1], ['contents' => $c2], ['contents' => $c3]]];
    }
    private function rowSidebarText($c1, $c2, $bg = 'white') {
        return ['cells' => [1, 3], 'backgroundColor' => $bg, 'columns' => [['contents' => $c1], ['contents' => $c2]]];
    }

    // --- COMPONENT BUILDERS ---
    private function text($align, $text, $color = '#4b5563', $size = '14px', $padding = '10px') {
        return ['type' => 'text', 'values' => ['text' => $text, 'textAlign' => strtolower($align), 'color' => $color, 'fontSize' => $size, 'padding' => $padding]];
    }
    private function heading($text, $color = '#111827', $align = 'left', $padding = '10px') {
        return ['type' => 'heading', 'values' => ['text' => $text, 'color' => $color, 'textAlign' => strtolower($align), 'padding' => $padding, 'fontSize' => '24px']];
    }
    private function img($size, $label) {
        return ['type' => 'image', 'values' => ['src' => ['url' => "https://via.placeholder.com/$size?text=$label"], 'textAlign' => 'center', 'padding' => '10px']];
    }
    
    private function getUniqueSubject($cat, $i) {
        return "[$cat] " . ($i % 2 == 0 ? "Important Security Update" : "Great news from the team!") . " (V7-$i)";
    }
}
