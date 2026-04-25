# Start PHP built-in server from repo root. Checks GD first. Stop old "php -S" with Ctrl+C if port busy.

$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $root

$phpExe = $null
$wingetGlob = Join-Path $env:LOCALAPPDATA 'Microsoft\WinGet\Packages\PHP.PHP*\php.exe'
$found = @(Get-Item $wingetGlob -ErrorAction SilentlyContinue | Sort-Object FullName -Descending)
if ($found.Count -ge 1) {
    $phpExe = $found[0].FullName
}
if (-not $phpExe) {
    $cmd = Get-Command php -ErrorAction SilentlyContinue
    if ($cmd) { $phpExe = $cmd.Source }
}
if (-not $phpExe -or -not (Test-Path $phpExe)) {
    Write-Host 'php.exe not found. Install PHP or add it to PATH.' -ForegroundColor Red
    exit 1
}

Write-Host "PHP: $phpExe" -ForegroundColor Cyan
& $phpExe -r "exit(extension_loaded('gd') ? 0 : 2);"
if ($LASTEXITCODE -ne 0) {
    Write-Host ''
    Write-Host 'ERROR: GD extension is not loaded for this php.exe.' -ForegroundColor Red
    Write-Host 'Enable extension=gd in php.ini next to this php.exe (see tasks/php_env.php in browser).' -ForegroundColor Yellow
    Write-Host ''
    exit 1
}

Write-Host 'GD: OK' -ForegroundColor Green
Write-Host "Document root: $root" -ForegroundColor Cyan
Write-Host 'Open: http://localhost:8000/tasks/task_07.php' -ForegroundColor Green
Write-Host 'Press Ctrl+C to stop.' -ForegroundColor DarkGray
& $phpExe -S localhost:8000 -t $root
