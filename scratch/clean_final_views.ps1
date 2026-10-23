# 1. dashboard.php
$file = "app/Views/admin/dashboard.php"
$c = [System.IO.File]::ReadAllText($file, [System.Text.Encoding]::UTF8)
$c = $c.Replace('style="background: rgba(74, 222, 128, 0.12); color: #4ade80;"', 'style="background: rgba(167, 173, 180, 0.12); color: #CBD0D6;"')
$c = $c.Replace('border-left: 3px solid #caa15c;', 'border-left: 3px solid #A7ADB4;')
$c = $c.Replace('background: rgba(202, 161, 92, 0.12);', 'background: rgba(167, 173, 180, 0.12);')
$c = $c.Replace('background: rgba(202, 161, 92, 0.15);', 'background: rgba(167, 173, 180, 0.15);')
$c = $c.Replace('Ouro Nobre, Prata 925, Esmeralda e Rose Gold', 'Prata Nobre & Noir, Branco Perolado & Platina e Grafite')
$c = $c.Replace('border-left: 3px solid #10b981;', 'border-left: 3px solid #737B85;')
[System.IO.File]::WriteAllText($file, $c, [System.Text.Encoding]::UTF8)

# 2. boutique.php
$file = "app/Views/admin/boutique.php"
$c = [System.IO.File]::ReadAllText($file, [System.Text.Encoding]::UTF8)
$c = $c.Replace('👑 Ouro Imperial DIVINIUM (#D4BA88)', '🪙 Prata Nobre Divinium (#A7ADB4)')
$c = $c.Replace('🖤 Ônix Nobre (#121110 com Dourado)', '🖤 Ônix & Grafite (#252525)')
$c = $c.Replace('🪙 Prata de Lei 925 (#E2E8F0)', '🪙 Prata de Lei 925 (#CBD0D6)')
$c = $c.Replace('💎 Verde Esmeralda (#10B981)', '⚪ Platina Pura (#F0F0EF)')
$c = $c.Replace('🌸 Rose Gold (#FB7185)', '🔘 Titânio Grafite (#737B85)')
$c = $c.Replace('Indicador Mineral da Joia (Ponto Dourado + Nome)', 'Indicador Mineral da Joia (Ponto de Prata + Nome)')
$c = $c.Replace("btn.style.background = '#10b981';", "btn.style.background = '#737B85';")
$c = $c.Replace('color: #caa15c;', 'color: #CBD0D6;')
$c = $c.Replace('#0D0C0B', '#F8F8F7')
$c = $c.Replace('#141312', '#FFFFFF')
$c = $c.Replace('accent-color: #caa15c;', 'accent-color: #737B85;')
$c = $c.Replace('Mármore Noir & Ouro', 'Mármore Noir & Prata')
$c = $c.Replace('Poeira Dourada ✦', 'Poeira de Estrelas ✦')
$c = $c.Replace('color: #d4ba88;', 'color: #CBD0D6;')
[System.IO.File]::WriteAllText($file, $c, [System.Text.Encoding]::UTF8)

# 3. lancamentos.php
$file = "app/Views/admin/lancamentos.php"
$c = [System.IO.File]::ReadAllText($file, [System.Text.Encoding]::UTF8)
$c = $c.Replace('background: #10b981;', 'background: #A7ADB4;')
$c = $c.Replace('accent-color: #10b981;', 'accent-color: #737B85;')
$c = $c.Replace('toast.style.cssText = `' + "`r`n" + '        position: fixed;' + "`r`n" + '        bottom: 25px;' + "`r`n" + '        right: 25px;' + "`r`n" + '        background: #10b981;', 'toast.style.cssText = `' + "`r`n" + '        position: fixed;' + "`r`n" + '        bottom: 25px;' + "`r`n" + '        right: 25px;' + "`r`n" + '        background: #252525; border: 1px solid rgba(167, 173, 180, 0.4);')
$c = $c.Replace('toast.style.cssText = `' + "`n" + '        position: fixed;' + "`n" + '        bottom: 25px;' + "`n" + '        right: 25px;' + "`n" + '        background: #10b981;', 'toast.style.cssText = `' + "`n" + '        position: fixed;' + "`n" + '        bottom: 25px;' + "`n" + '        right: 25px;' + "`n" + '        background: #252525; border: 1px solid rgba(167, 173, 180, 0.4);')
[System.IO.File]::WriteAllText($file, $c, [System.Text.Encoding]::UTF8)

# 4. products.php
$file = "app/Views/admin/products.php"
$c = [System.IO.File]::ReadAllText($file, [System.Text.Encoding]::UTF8)
$c = $c.Replace('<option value="emerald" ${btnColor === ''emerald'' ? ''selected'' : ''''}>💎 Verde Esmeralda (#10B981)</option>', '<option value="platinum" ${btnColor === ''platinum'' ? ''selected'' : ''''}>⚪ Platina Pura (#CBD0D6)</option>')
$c = $c.Replace("bgStyle = 'linear-gradient(135deg, #065f46 0%, #10b981 100%)';", "bgStyle = 'linear-gradient(135deg, #CBD0D6 0%, #A7ADB4 100%)'; textColor = '#202020';")
[System.IO.File]::WriteAllText($file, $c, [System.Text.Encoding]::UTF8)

Write-Output "Cleaned final views"
