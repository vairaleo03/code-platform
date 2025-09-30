FROM bitnamilegacy/laravel:latest

USER root

# Impostiamo il timezone per Italia
ENV TZ=Europe/Rome
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Ambiente di default
ARG APP_ENV=development
ENV APP_ENV=${APP_ENV}

WORKDIR /app

# Copia prima TUTTI i file dell'applicazione tranne .env
COPY . .

# DEBUG: Verifica contenuto e permessi
RUN ls -la /app && \
    ls -la /app/artisan

# Imposta permessi su artisan
RUN chmod +x /app/artisan

# Crea directory per Google Drive credentials e imposta permessi
RUN mkdir -p /app/storage/app/google && \
    chmod 755 /app/storage/app/google

# Installa le dipendenze PHP (inclusa Google Drive)
RUN if [ "$APP_ENV" = "development" ]; then \
        echo "Installing development dependencies..." && \
        composer install --optimize-autoloader --no-interaction; \
    else \
        echo "Installing production dependencies..." && \
        composer install --no-dev --optimize-autoloader --no-interaction; \
    fi

# Aggiungi dipendenze Google Drive se non già presenti
RUN composer require google/apiclient:^2.15 --no-interaction --quiet || true

# Installa le dipendenze Node e compila gli assets per CO.DE Platform
RUN if [ -f package-lock.json ]; then \
        npm ci --no-audit --no-fund; \
    else \
        npm install --no-audit --no-fund; \
    fi && \
    NODE_ENV=production npm run build

# Imposta i permessi corretti per Laravel
RUN chown -R daemon:root /app \
    && chmod -R g+w /app/storage /app/bootstrap/cache

# Crea directory per uploads e imposta permessi
RUN mkdir -p /app/storage/app/uploads && \
    chmod -R 775 /app/storage && \
    chown -R daemon:daemon /app/storage

# Imposta la configurazione per CO.DE Platform
RUN php artisan storage:link || true

# Assicura che le directory necessarie esistano
RUN mkdir -p /app/public/css /app/public/js /app/public/images

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]