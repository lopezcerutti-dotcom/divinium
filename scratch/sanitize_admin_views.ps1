# 1. dashboard.php
$dashFile = "app/Views/admin/dashboard.php"
$c = [System.IO.File]::ReadAllText($dashFile, [System.Text.Encoding]::UTF8)
$c = $c.Replace('border-left: 3px solid #34d399;', 'border-left: 3px solid #A7ADB4;')
$c = $c.Replace('background: rgba(52, 211, 153, 0.12); color: #34d399;', 'background: rgba(167, 173, 180, 0.12); color: #CBD0D6;')
$c = $c.Replace('background: rgba(52, 211, 153, 0.15); color: #34d399;', 'background: rgba(167, 173, 180, 0.15); color: #FFFFFF;')
$c = $c.Replace('background: rgba(16, 185, 129, 0.12); color: #34d399;', 'background: rgba(167, 173, 180, 0.12); color: #CBD0D6;')
$c = $c.Replace('color: #caa15c;', 'color: #CBD0D6;')
$c = $c.Replace('color: #d4ba88;', 'color: #CBD0D6;')
$c = $c.Replace('color: #34d399;', 'color: #CBD0D6;')
$c = $c.Replace('accent-color: #d4ba88;', 'accent-color: #737B85;')
$c = $c.Replace("resDiv.style.color = '#34d399';", "resDiv.style.color = '#FFFFFF';")
$c = $c.Replace('background: rgba(202, 161, 92, 0.1); border: 1px solid rgba(202, 161, 92, 0.25); border-radius: 8px; padding: 12px 16px; margin-top: 6px; font-size: 0.8rem; color: #d4ba88;', 'background: rgba(167, 173, 180, 0.1); border: 1px solid rgba(167, 173, 180, 0.3); border-radius: 8px; padding: 12px 16px; margin-top: 6px; font-size: 0.8rem; color: #CBD0D6;')
$c = $c.Replace('<h4 style="color: #34d399;', '<h4 style="color: #FFFFFF;')
[System.IO.File]::WriteAllText($dashFile, $c, [System.Text.Encoding]::UTF8)

# 2. boutique.php
$boutFile = "app/Views/admin/boutique.php"
$c = [System.IO.File]::ReadAllText($boutFile, [System.Text.Encoding]::UTF8)
$c = $c.Replace('color: #d4ba88;', 'color: #CBD0D6;')
$c = $c.Replace('color: #caa15c;', 'color: #CBD0D6;')
$c = $c.Replace('accent-color: #caa15c;', 'accent-color: #737B85;')
$c = $c.Replace('#0D0C0B', '#252525')
$lines = [System.IO.File]::ReadAllLines($boutFile, [System.Text.Encoding]::UTF8)
for ($i = 0; $i -lt $lines.Length; $i++) {
    if ($lines[$i] -match 'Ouro Imperial DIVINIUM') {
        $lines[$i] = $lines[$i] -replace '👑 Ouro Imperial DIVINIUM \(#D4BA88\)', '🪙 Prata Nobre Divinium (#A7ADB4)'
    }
}
$c = [string]::Join("`r`n", $lines)
[System.IO.File]::WriteAllText($boutFile, $c, [System.Text.Encoding]::UTF8)

# 3. categories.php
$catFile = "app/Views/admin/categories.php"
$c = [System.IO.File]::ReadAllText($catFile, [System.Text.Encoding]::UTF8)
$c = $c.Replace('background: rgba(74, 222, 128, 0.1); color: #4ade80;', 'background: rgba(167, 173, 180, 0.15); color: #FFFFFF;')
[System.IO.File]::WriteAllText($catFile, $c, [System.Text.Encoding]::UTF8)

# 4. lancamentos.php
$lanFile = "app/Views/admin/lancamentos.php"
$c = [System.IO.File]::ReadAllText($lanFile, [System.Text.Encoding]::UTF8)
$c = $c.Replace('accent-color: #d4ba88;', 'accent-color: #737B85;')
$c = $c.Replace('border-color: #d4ba88;', 'border-color: #737B85;')
$c = $c.Replace('border-bottom-color: #d4ba88;', 'border-bottom-color: #CBD0D6;')
$c = $c.Replace('background: #0d0c0b;', 'background: #202020;')
$c = $c.Replace('rgba(212,186,136,0.3)', 'rgba(167,173,180,0.3)')
$c = $c.Replace('rgba(212,186,136,0.4)', 'rgba(167,173,180,0.4)')
$c = $c.Replace('color: #d4ba88;', 'color: #CBD0D6;')
$c = $c.Replace('color: #caa15c;', 'color: #CBD0D6;')
$c = $c.Replace('background: #d4ba88;' + "`r`n" + '    color: #0d0c0b;', 'background: #CBD0D6;' + "`r`n" + '    color: #202020;' + "`r`n" + '    font-weight: 700;')
$c = $c.Replace('background: #d4ba88;' + "`n" + '    color: #0d0c0b;', 'background: #CBD0D6;' + "`n" + '    color: #202020;' + "`n" + '    font-weight: 700;')
$c = $c.Replace('linear-gradient(135deg, #d4ba88, #caa15c)', 'linear-gradient(135deg, #CBD0D6, #A7ADB4)')
$c = $c.Replace('color: #0d0c0b;', 'color: #202020;')
[System.IO.File]::WriteAllText($lanFile, $c, [System.Text.Encoding]::UTF8)

# 5. landing_page.php
$lpFile = "app/Views/admin/landing_page.php"
$c = [System.IO.File]::ReadAllText($lpFile, [System.Text.Encoding]::UTF8)
$c = $c.Replace('#0d0c0b', '#252525')
$c = $c.Replace('#0D0C0B', '#252525')
$c = $c.Replace('#d4ba88', '#CBD0D6')
$c = $c.Replace('#caa15c', '#CBD0D6')
$c = $c.Replace('#CAA15C', '#A7ADB4')
$c = $c.Replace('status.style.color = ''#4ade80'';', 'status.style.color = ''#FFFFFF'';')
$c = $c.Replace('about_tag_color: ''#4ade80''', 'about_tag_color: ''#CBD0D6''')
[System.IO.File]::WriteAllText($lpFile, $c, [System.Text.Encoding]::UTF8)

# 6. metrics.php
$metFile = "app/Views/admin/metrics.php"
$c = [System.IO.File]::ReadAllText($metFile, [System.Text.Encoding]::UTF8)
$c = $c.Replace('#caa15c', '#CBD0D6')
$c = $c.Replace('#4ade80', '#FFFFFF')
$c = $c.Replace('#14161a', '#252525')
$c = $c.Replace('rgba(202, 161, 92, 0.3)', 'rgba(167, 173, 180, 0.35)')
$c = $c.Replace('rgba(202, 161, 92, 0.15)', 'rgba(167, 173, 180, 0.15)')
$c = $c.Replace('rgba(202, 161, 92, 0.1)', 'rgba(167, 173, 180, 0.1)')
$c = $c.Replace('rgba(202,161,92,0.6)', 'rgba(167,173,180,0.6)')
$c = $c.Replace('rgba(74, 222, 128, 0.12)', 'rgba(167, 173, 180, 0.12)')
$c = $c.Replace('rgba(74, 222, 128, 0.1)', 'rgba(167, 173, 180, 0.15)')
$c = $c.Replace('rgba(74, 222, 128, 0.25)', 'rgba(167, 173, 180, 0.25)')
$c = $c.Replace('linear-gradient(180deg, #caa15c 0%, rgba(202, 161, 92, 0.25) 100%)', 'linear-gradient(180deg, #CBD0D6 0%, rgba(167, 173, 180, 0.25) 100%)')
$c = $c.Replace('linear-gradient(90deg, #caa15c, #e0c080)', 'linear-gradient(90deg, #CBD0D6, #A7ADB4)')
[System.IO.File]::WriteAllText($metFile, $c, [System.Text.Encoding]::UTF8)

Write-Output "All 6 admin views sanitized successfully"
