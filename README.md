# Yaarpool

A WhatsApp ridesharing bot. Members of a group (or DM) post offers ("driving Pune → Mumbai Sat 9am, 3 seats") and requests ("need a lift Andheri → BKC tomorrow 8am") in natural language; Yaarpool detects intent and persists, lists, edits, or cancels rides on their behalf.

## How it works

Inbound WhatsApp messages are pulled from a local `wacli` SQLite store and routed to `App\Ai\Agents\YaarpoolAgent`. The agent is built on the Laravel AI SDK and backed by Google Gemini by default. Depending on intent, it calls one of five tools:

| Tool | Purpose | Owner-only |
|---|---|---|
| `ride_request` | Persist a passenger looking for a lift | — |
| `ride_create` | Persist a driver publishing a trip | — |
| `ride_list` | List upcoming rides in the current chat | — |
| `ride_update` | Edit a ride the user previously posted | ✓ |
| `ride_delete` | Cancel a ride the user previously posted | ✓ |

Owner-only tools refuse the call unless both the chat JID and sender JID on the ride match the inbound WhatsApp message; rides in other chats are treated as not-found rather than surfaced.

WhatsApp transport is handled by [`jigar-dhulla/laravel-whatsapp-ai-agent`](https://github.com/jigar-dhulla/laravel-whatsapp-ai-agent), which in turn relies on the external `wacli` sync daemon to maintain `~/.wacli/wacli.db`.

## Requirements

- PHP 8.3+
- A Gemini API key (`GEMINI_API_KEY`)
- `wacli sync --follow --refresh-contacts --refresh-groups` running externally to keep `~/.wacli/wacli.db` populated

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

Add `GEMINI_API_KEY` to `.env`, then wire your group / DM JID into `config/whatsapp-agent.php`. Discover JIDs with:

```bash
php artisan wa:chats     # list 1:1 chats
php artisan wa:groups    # list groups
php artisan wa:status    # verify which agent is wired to which chat
```

## Running

Start the app stack (HTTP server, queue worker, log tail, Vite):

```bash
composer run dev
```

Start the WhatsApp listener daemon in a separate terminal:

```bash
php artisan wa:listen          # add -vvv to log every scanned message
```

## Run with Docker

A `Dockerfile` and `docker-compose.yml` are included if you'd rather not install PHP or `wacli` on the host. The image bundles PHP 8.4, Composer, and a Linux build of `wacli`; compose runs four services off the same image:

- `wacli` — the `wacli sync` daemon that keeps `~/.wacli/wacli.db` populated.
- `wa-listen` — `php artisan wa:listen`.
- `queue` — the database queue worker.
- `app` — on-demand shell for ad-hoc artisan / composer / tests (in the `cli` profile, so it doesn't start with `up`).

The project source is bind-mounted at `/app` and the host's `~/.wacli` is bind-mounted into each container so the existing WhatsApp pairing is reused. **Stop any host-side `wacli sync` first** to avoid two daemons writing to the same SQLite file.

```bash
docker compose build
docker compose run --rm app composer install
docker compose run --rm app php artisan migrate
docker compose up -d wacli wa-listen queue
```

Ad-hoc artisan, composer, or tests go through the `app` service:

```bash
docker compose run --rm app php artisan test --compact
docker compose run --rm app php artisan wa:status
docker compose logs -f wa-listen
```

To pin a different `wacli` release, pass `--build-arg WACLI_VERSION=<version>` to `docker compose build`.

## Tests

```bash
php artisan test --compact
```

## Project structure

- `app/Ai/Agents/YaarpoolAgent.php` — the registered agent; receives the inbound message and orchestrates tool calls.
- `app/Ai/Tools/` — one file per tool (`RideRequestTool`, `RideCreateTool`, `RideListTool`, `RideUpdateTool`, `RideDeleteTool`).
- `app/Models/Ride.php` and the `rides` table — canonical store for both ride requests and offers, distinguished by a `type` enum.
- `config/whatsapp-agent.php` — registers the agent FQCN against chat/group JIDs.

## License

Yaarpool is licensed under the [GNU Affero General Public License v3.0 (AGPL-3.0-only)](https://www.gnu.org/licenses/agpl-3.0.html). The full text is in [`LICENSE`](LICENSE).
