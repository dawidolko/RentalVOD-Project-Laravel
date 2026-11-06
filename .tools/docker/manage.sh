#!/bin/bash

# Kolory
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
DOCKER_DIR="$PROJECT_DIR/.tools/docker"

cd "$DOCKER_DIR"

case "$1" in
    start)
        echo -e "${BLUE}🚀 Starting project RentalVOD...${NC}"
        docker compose up -d
        sleep 3
        echo -e "${GREEN}✅ Project started!${NC}"
        echo -e "${GREEN}📍 Aplikacja: http://localhost:8000${NC}"
        echo -e "${GREEN}📊 phpMyAdmin: http://localhost:8080${NC}"
        ;;
    
    stop)
        echo -e "${YELLOW}⏸️  Stopping project...${NC}"
        docker compose down
        echo -e "${GREEN}✅ Project stopped${NC}"
        ;;
    
    restart)
        echo -e "${YELLOW}🔄 Restart projektu...${NC}"
        docker compose restart
        echo -e "${GREEN}✅ Project restarted${NC}"
        ;;
    
    build)
        echo -e "${BLUE}🔨 Rebuilding project from scratch...${NC}"
        docker compose down
        docker compose up -d --build
        echo -e "${GREEN}✅ Project built and started!${NC}"
        ;;
    
    logs)
        echo -e "${BLUE}📋 Showing logs...${NC}"
        docker compose logs -f app
        ;;
    
    shell)
        echo -e "${BLUE}🐚 Entering the application container...${NC}"
        docker exec -it rentalvod_app bash
        ;;
    
    db)
        echo -e "${BLUE}🗄️  Entering MySQL...${NC}"
        docker exec -it rentalvod_db mysql -u rentalvod -psecret rentalvod
        ;;
    
    seed)
        echo -e "${BLUE}📊 Seeding the database...${NC}"
        docker exec rentalvod_app php artisan db:seed --force
        echo -e "${GREEN}✅ Database seeded with test data${NC}"
        ;;
    
    fresh)
        echo -e "${YELLOW}⚠️  Wiping and recreating the database...${NC}"
        docker exec rentalvod_app php artisan migrate:fresh --seed --force
        echo -e "${GREEN}✅ Database refreshed${NC}"
        ;;
    
    reset)
        echo -e "${RED}🗑️  Removing all data...${NC}"
        docker compose down -v
        docker rmi rentalvod-project-app 2>/dev/null || true
        echo -e "${GREEN}✅ Project reset${NC}"
        echo -e "${BLUE}Uruchom: $0 build${NC}"
        ;;
    
    status)
        echo -e "${BLUE}📊 Container status:${NC}"
        docker compose ps
        ;;
    
    *)
        echo -e "${BLUE}RentalVOD - Docker Management Script${NC}"
        echo ""
        echo "Usage: $0 {command}"
        echo ""
        echo "Available commands:"
        echo "  ${GREEN}start${NC}    - Start the project"
        echo "  ${YELLOW}stop${NC}     - Stop the project"
        echo "  ${YELLOW}restart${NC}  - Restart the project"
        echo "  ${BLUE}build${NC}    - Rebuild and start the project"
        echo "  ${BLUE}logs${NC}     - Show application logs"
        echo "  ${BLUE}shell${NC}    - Enter the application container"
        echo "  ${BLUE}db${NC}       - Enter MySQL"
        echo "  ${BLUE}seed${NC}     - Seed the database with test data"
        echo "  ${YELLOW}fresh${NC}    - Refresh the database (migrate:fresh --seed)"
        echo "  ${RED}reset${NC}    - Reset the project (removes everything)"
        echo "  ${BLUE}status${NC}   - Container status"
        echo ""
        exit 1
        ;;
esac
