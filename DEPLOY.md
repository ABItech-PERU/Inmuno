# Guía de Despliegue - InmunoAlerta

## Problema Actual
El proyecto está encontrando problemas de compatibilidad con Node.js 18.20.8 en el servidor de producción.

## Soluciones

### Opción 1: Actualizar Node.js en el servidor (RECOMENDADO)

```bash
# Instalar nvm si no está instalado
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
source ~/.bashrc

# Instalar y usar Node.js 20
nvm install 20.18.0
nvm use 20.18.0
nvm alias default 20.18.0

# Verificar la versión
node --version

# Continuar con el build normal
npm ci
npm run build
```

### Opción 2: Usar configuración compatible con Node.js 18

Si no puedes actualizar Node.js, usa el script de build específico para producción:

```bash
# Limpiar node_modules y reinstalar dependencias
rm -rf node_modules package-lock.json
npm install

# Usar el build de producción compatible
npm run build:production
```

### Opción 3: Build local y subir archivos

Si las opciones anteriores no funcionan, puedes compilar localmente y subir los archivos:

```bash
# En tu máquina local (Windows)
npm run build

# Subir estos directorios al servidor:
# - public/build/
# - bootstrap/ssr/
```

## Cambios Realizados

1. **Downgrade de dependencias críticas:**
   - Vite: 7.0.4 → 5.4.8
   - @vitejs/plugin-vue: 6.0.0 → 5.1.4
   - laravel-vite-plugin: 2.0.0 → 1.0.5

2. **Script de build simplificado:**
   - Eliminado build duplicado
   - Agregado script específico para producción

3. **Configuración de Vite optimizada:**
   - Target específico para Node.js 18
   - Polyfills para compatibilidad
   - Optimizaciones de build

## Notas Importantes

- La versión recomendada de Node.js está especificada en `.nvmrc`
- El error `crypto.hash is not a function` se debe a incompatibilidades entre Vite 7 y Node.js 18
- Las dependencias ajustadas mantienen toda la funcionalidad pero con mejor compatibilidad
