# Vercel Environment Checklist

Set these in **Vercel → Project → Settings → Environment Variables** for Production.

```env
APP_KEY=base64:<generate-with-php-artisan-key-generate---show>
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<your-project>.vercel.app
LOG_CHANNEL=stderr
VIEW_COMPILED_PATH=/tmp
CACHE_STORE=array
SESSION_DRIVER=database
QUEUE_CONNECTION=sync

DB_CONNECTION=mysql
DB_HOST=<TiDB-host>
DB_PORT=4000
DB_DATABASE=<database-name>
DB_USERNAME=<TiDB-username-prefix,-for-example-xxxx.root>
DB_PASSWORD=<TiDB-password>
MYSQL_ATTR_SSL_CA=/etc/pki/tls/certs/ca-bundle.crt
# If TiDB SSL fails, try:
# MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt

# Not used for production uploads (uploads go to Vercel Blob).
FILESYSTEM_DISK=local

# Vercel Blob (public access). Created automatically by Vercel when you create a
# Blob store in the project (Storage tab) -- do not type it by hand on Vercel.
# Local development: `vercel link` then `vercel env pull .env.local`, and copy
# BLOB_READ_WRITE_TOKEN into .env (Laravel does not read .env.local on its own).
BLOB_READ_WRITE_TOKEN=<set-automatically-by-Vercel-Blob-store>
```

### Legacy R2 variables -- REMOVE after the old files are migrated and verified

Keep these until every old file opens correctly from Blob (or you decide no old files need migrating). Then delete them here, run `composer remove league/flysystem-aws-s3-v3`, and delete the R2 bucket.

```env
AWS_ACCESS_KEY_ID=<R2-access-key-id>
AWS_SECRET_ACCESS_KEY=<R2-secret-access-key>
AWS_DEFAULT_REGION=auto
AWS_BUCKET=<R2-bucket-name>
AWS_ENDPOINT=https://<ACCOUNT_ID>.r2.cloudflarestorage.com
AWS_USE_PATH_STYLE_ENDPOINT=true
AWS_URL=https://<public-r2-domain-or-custom-domain>
```

`AWS_URL` is still read by `App\Services\BlobStorage::url()` to display legacy rows that hold an R2 relative path.

## Manual steps

1. TiDB Cloud Starter: cluster, database, credentials (done), then `php artisan migrate --force` against TiDB (done).
2. Vercel project → **Storage** → create a **Blob** store with **Public** access. The token is injected into the project env; **redeploy** afterwards.
3. Add the other variables above in Vercel Dashboard. Never commit secrets, `.env`, or `.env.local`.
4. Import the GitHub repository into Vercel (framework: Other) and deploy.
5. Old files (only if any exist): see the migration step in the final summary. Do not delete anything in R2 before verifying.
6. After verification: remove the `AWS_*` variables, `league/flysystem-aws-s3-v3`, and the R2 bucket.

## Compatibility notes

- Vercel Function request bodies are limited to about 4.5 MB in total. Each photo is validated to 4 MB, but the gallery accepts several files per request: upload a few small photos at a time.
- Vercel Blob on Hobby allows 2,000 advanced operations per month (put, copy, list). Every upload is at least one operation.
- Queue workers and the Laravel scheduler do not run on Vercel; `QUEUE_CONNECTION=sync`.
- Vercel Hobby is intended for non-commercial use.
- Photo columns now store the full Blob URL. Legacy basenames resolve to `public/image/`; legacy relative paths resolve to R2 (`AWS_URL`).
- TiDB migrations may need adjustment for unsupported MySQL features or index constraints; review `migrate --pretend` output first.
