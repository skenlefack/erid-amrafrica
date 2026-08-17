#!/bin/bash
# ERID-AMRAfrica FTP Deployment Script (Infomaniak)
set -e

FTP_HOST="ng5go.ftp.infomaniak.com"
FTP_USER="ng5go_erid-amrafrica"
PROJECT_DIR="/c/dev/erid-amrafrica"

# Load FTP password from .env or prompt
if [ -f "${PROJECT_DIR}/.env" ] && grep -q '^FTP_PASS=' "${PROJECT_DIR}/.env"; then
    FTP_PASS=$(grep '^FTP_PASS=' "${PROJECT_DIR}/.env" | cut -d'=' -f2-)
else
    read -sp "FTP Password for ${FTP_USER}: " FTP_PASS
    echo ""
fi

FTP_URL="ftp://${FTP_HOST}"
CURL_OPTS="--ftp-ssl --ftp-create-dirs --user ${FTP_USER}:${FTP_PASS}"

upload_file() {
    local local_path="$1"
    local remote_path="$2"
    echo "  Uploading: ${remote_path}"
    curl -s ${CURL_OPTS} -T "${local_path}" "${FTP_URL}/${remote_path}" 2>/dev/null
}

echo "=== ERID-AMRAfrica Deployment ==="
echo ""

# 1. Upload root .htaccess (redirect to public/)
echo "[1/7] Root .htaccess..."
upload_file "${PROJECT_DIR}/deploy.htaccess" ".htaccess"

# 2. Upload .env
echo "[2/7] .env configuration..."
upload_file "${PROJECT_DIR}/.env" ".env"

# 3. Upload public/ files
echo "[3/7] Public files..."
upload_file "${PROJECT_DIR}/public/index.php" "public/index.php"
upload_file "${PROJECT_DIR}/public/.htaccess" "public/.htaccess"
upload_file "${PROJECT_DIR}/public/assets/css/app.css" "public/assets/css/app.css"
upload_file "${PROJECT_DIR}/public/assets/js/app.js" "public/assets/js/app.js"

# 4. Upload config/
echo "[4/7] Config..."
upload_file "${PROJECT_DIR}/config/routes.php" "config/routes.php"

# 5. Upload app/Core/
echo "[5/7] Core framework..."
for f in "${PROJECT_DIR}"/app/Core/*.php; do
    fname=$(basename "$f")
    upload_file "$f" "app/Core/${fname}"
done

# 6. Upload app/Controllers/
echo "[6/7] Controllers..."
for f in "${PROJECT_DIR}"/app/Controllers/Admin/*.php; do
    fname=$(basename "$f")
    upload_file "$f" "app/Controllers/Admin/${fname}"
done
for f in "${PROJECT_DIR}"/app/Controllers/Public/*.php; do
    fname=$(basename "$f")
    upload_file "$f" "app/Controllers/Public/${fname}"
done

# 7. Upload app/Views/ and app/lang/
echo "[7/7] Views & Languages..."
for f in "${PROJECT_DIR}"/app/Views/admin/*.php; do
    fname=$(basename "$f")
    upload_file "$f" "app/Views/admin/${fname}"
done
for f in "${PROJECT_DIR}"/app/Views/layouts/*.php; do
    fname=$(basename "$f")
    upload_file "$f" "app/Views/layouts/${fname}"
done
for f in "${PROJECT_DIR}"/app/Views/public/*.php; do
    fname=$(basename "$f")
    upload_file "$f" "app/Views/public/${fname}"
done
for f in "${PROJECT_DIR}"/app/lang/*.php; do
    fname=$(basename "$f")
    upload_file "$f" "app/lang/${fname}"
done

echo ""
echo "=== Deployment complete! ==="
