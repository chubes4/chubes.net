# Chubes Contact

Contact form block (`chubes/contact-form`) for chubes.net. Submissions POST to `/wp-json/chubes/v1/contact`, which checks a nonce, a honeypot and a timestamp, then emails the site admin and the sender.

```bash
npm ci
npm run build   # src/ -> build/
```
