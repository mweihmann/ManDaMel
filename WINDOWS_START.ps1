Write-Host "Stopping old containers..."
docker compose down -v --remove-orphans

Write-Host "Starting Docker services..."
docker compose up -d --build

Write-Host "Opening Backend in VS Code..."
code ./backend

Write-Host "Opening Frontend in VS Code..."
code ./frontend
