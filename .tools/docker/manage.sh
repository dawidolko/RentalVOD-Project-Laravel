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
        echo -e "${BLUE}🚀 Uruchamianie projektu RentalVOD...${NC}"
        docker compose up -d
        sleep 3
        echo -e "${GREEN}✅ Projekt uruchomiony!${NC}"
        echo -e "${GREEN}📍 Aplikacja: http://localhost:8000${NC}"
        echo -e "${GREEN}📊 phpMyAdmin: http://localhost:8080${NC}"
        ;;
    
    stop)
        echo -e "${YELLOW}⏸️  Zatrzymywanie projektu...${NC}"
        docker compose down
        echo -e "${GREEN}✅ Projekt zatrzymany${NC}"
        ;;
    
    restart)
        echo -e "${YELLOW}🔄 Restart projektu...${NC}"
        docker compose restart
        echo -e "${GREEN}✅ Projekt zrestartowany${NC}"
        ;;
    
    build)
        echo -e "${BLUE}🔨 Budowanie projektu od nowa...${NC}"
        docker compose down
        docker compose up -d --build
        echo -e "${GREEN}✅ Projekt zbudowany i uruchomiony!${NC}"
        ;;
    
    logs)
        echo -e "${BLUE}📋 Wyświetlanie logów...${NC}"
        docker compose logs -f app
        ;;
    
    shell)
        echo -e "${BLUE}🐚 Wchodzenie do kontenera aplikacji...${NC}"
        docker exec -it rentalvod_app bash
        ;;
    
    db)
        echo -e "${BLUE}🗄️  Wchodzenie do MySQL...${NC}"
        docker exec -it rentalvod_db mysql -u rentalvod -psecret rentalvod
        ;;
    
    seed)
        echo -e "${BLUE}📊 Wypełnianie bazy danymi...${NC}"
        docker exec rentalvod_app php artisan db:seed --force
        echo -e "${GREEN}✅ Baza wypełniona danymi testowymi${NC}"
        ;;
    
    fresh)
        echo -e "${YELLOW}⚠️  Czyszczenie i ponowne tworzenie bazy danych...${NC}"
        docker exec rentalvod_app php artisan migrate:fresh --seed --force
        echo -e "${GREEN}✅ Baza danych odświeżona${NC}"
        ;;
    
    reset)
        echo -e "${RED}🗑️  Usuwanie wszystkich danych...${NC}"
        docker compose down -v
        docker rmi rentalvod-project-app 2>/dev/null || true
        echo -e "${GREEN}✅ Projekt zresetowany${NC}"
        echo -e "${BLUE}Uruchom: $0 build${NC}"
        ;;
    
    status)
        echo -e "${BLUE}📊 Status kontenerów:${NC}"
        docker compose ps
        ;;
    
    *)
        echo -e "${BLUE}RentalVOD - Docker Management Script${NC}"
        echo ""
        echo "Użycie: $0 {komenda}"
        echo ""
        echo "Dostępne komendy:"
        echo "  ${GREEN}start${NC}    - Uruchomienie projektu"
        echo "  ${YELLOW}stop${NC}     - Zatrzymanie projektu"
        echo "  ${YELLOW}restart${NC}  - Restart projektu"
        echo "  ${BLUE}build${NC}    - Przebudowanie i uruchomienie projektu"
        echo "  ${BLUE}logs${NC}     - Wyświetlenie logów aplikacji"
        echo "  ${BLUE}shell${NC}    - Wejście do kontenera aplikacji"
        echo "  ${BLUE}db${NC}       - Wejście do MySQL"
        echo "  ${BLUE}seed${NC}     - Wypełnienie bazy danymi testowymi"
        echo "  ${YELLOW}fresh${NC}    - Odświeżenie bazy danych (migrate:fresh --seed)"
        echo "  ${RED}reset${NC}    - Reset projektu (usuwa wszystko)"
        echo "  ${BLUE}status${NC}   - Status kontenerów"
        echo ""
        exit 1
        ;;
esac
