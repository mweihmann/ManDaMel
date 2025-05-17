echo "Stopping old containers..."
docker compose down -v --remove-orphans

echo "Starting Docker services..."
docker compose up -d --build

# echo "Opening Backend in VS Code..."
# code ./backend

# echo "Opening Frontend in VS Code..."
# code ./frontend