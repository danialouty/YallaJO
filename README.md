# YallaJO
YallaJO is a full-featured outdoor tourism and activity reservation platform built with Laravel 12 and MySQL. It connects adventurers across Jordan with verified tourism offices, local hike groups, and certified guides—complete with AI-powered itinerary generation, an interactive assistant, community hubs, and administrative oversight.

## This Project was done by:
| Name | Role | Github |
| ---- | ---- | ------ |
| Dania Daaseen | Scrum Master | [@danialouty](https://github.com/danialouty) |
| Sondos Nazzal | Product Owner | [@SondosNazzal04](https://github.com/SondosNazzal04) |
| Ahmad Bata | Developer | [@Ahmad-Batta](https://github.com/Ahmad-Batta) |
| Amro Abdelqader | Developer | [@amro-abdelqader-03](https://github.com/amro-abdelqader-03) |
| Motasem Alqam | Developer | [@MotasemAlqam1](https://github.com/MotasemAlqam1) |

## Relative Links:
| | |
|---|---|
| **Github** | https://github.com/SondosNazzal04/YallaJO |
| **Trello** | https://trello.com/invite/b/6afd4b2a727c2913e5684cf7/ATTIca9fda1c94f25115ebc69f33810e4724CFA6AB0B/adventurebook |
| **Figma** | https://www.figma.com/design/0xC6nBqp8wMbup6XAtkixc/adventure?node-id=0-1&t=0YIJV8XgEWISLqcg-1 |
| **miro** | https://miro.com/welcomeonboard/d3NYN0F1elBWS29ucnpXR0E4RTFmSzh2NVlqdS96cTJ5Y05icmQrYWJTNUxRR0huWHIyWEFTQzJObkR4MTlrYnNzSi83UlJ4c1ByS0lsRDE2dkdzVDhsZDBPWDhZaGpnVWVNY3dsdmFncjMrU3BGNmw5dFpJeFV2V0pmcUJDTmpBd044SHFHaVlWYWk0d3NxeHNmeG9BPT0hdjE=?share_link_id=667285150353 |

## Core Features
1. **Multi-Role System & Access Control**\
    **Roles:** Admin, Activity_Organizer, and Adventurer enforced via custom middleware (EnsureUserIsAdmin, EnsureOrganizerIsApproved) and authorization policies (AdventurePolicy, BookingPolicy, PaymentPolicy).\
    **Organizer Verification Workflow:** Organizers register as a licensed Tourism Office (tourism_office) or Hike Group / Social Page (hike_group) via modal profiles (ActivityOrganizerController), remaining blocked from publishing activities until approved by an administrator.\
2. **Adventures, Schedules & Guides Management**\
    **Adventure Listings & Profiles:** Organizers publish multi-day or single-day Jordanian adventures (AdventureController, AdventureDay) with custom media galleries (ImagesController).\
    **Scheduling & Guide Assignment:** Manage upcoming trip dates (AdventureSchedulesController), guide rosters (GuidesController), and guide availability calendars (GuidesAvailabilitiesController).
3. **Bookings, Payments & Reviews**\
    **Reservation Flow:** Adventurers browse, filter, and book adventures (BookingController) with dedicated form request validation (CreateBookingValidationRequest, UpdateBookingValidationRequest).\
    **Payments & Verified Reviews:** Integrated payment processing (PaymentController) and post-trip review/rating system (ReviewController) restricted to verified bookings, including review reporting capabilities.
4. **AI Services & Communities**\
    **AI Day-by-Day Itinerary Generator:** Powered by local Ollama integration (OllamaAdventureDayGenerator, config/ollama.php) to automatically generate structured daily itineraries for adventures.\
    **Interactive Chatbot Assistant:** Built-in conversational assistant (ChatbotController, chatbot.blade.php) to help adventurers discover trips and navigate the platform.\
    **Adventurer Communities:** Community groups (CommunityController), membership management (CommunityMembershipController), and real-time group messaging/discussions (CommunityMessageController).\
5. **Comprehensive Admin Suite & Localization**\
    **Admin Control Panel (app/Http/Controllers/Admin/):** Full CRUD and oversight across Users, Organizers (AdminOrganizerController), Adventures, Bookings, Payments, Reviews, Fraud Reports (AdminFraudReportController), and platform analytics (DashboardController).
    **Bilingual Support:** English and Arabic localization (lang/ar.json).
## Tech Stack
- Backend: PHP 8.2+, Laravel 12
- Database: MySQL (yallajo)
- Frontend: Blade Components, Custom Modular CSS & Tailwind CSS, Vanilla JS Components, Vite
- Authentication: Laravel Breeze
- AI Integration: Ollama (config/ollama.php)
- Testing: Pest PHP / PHPUnit
- Database Schema Overview
```
users — Accounts with role (Admin, Adventurer, Activity_Organizer) and phone_number.
activity_organizers & adventurers — Role-specific profiles and organizer approval audit columns (approved_by, approved_at).
adventures, adventure_days, & adventure_schedules — Trip details, daily breakdowns, and scheduled departure dates.
guides & guides_availabilities — Tour guide profiles and availability slots.
bookings, payments, & reviews — Reservations, transaction logs, ratings, and fraud/abuse reporting.
images — Media attachments for adventures and profiles.
communities, community_memberships, & community_messages — Adventurer communities, memberships, and group discussions.
```
## Local Setup & Installation
1. Clone & Install Dependencies
```
git clone <your-repository-url>
cd YallaJO
composer install
npm install
```
​
2. Environment Configuration
```
cp .env.example .env
php artisan key:generate
```
​
Configure your database and Ollama settings in .env:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yallajo
DB_USERNAME=root
DB_PASSWORD=

OLLAMA_BASE_URL=http://localhost:11434
OLLAMA_MODEL=llama3
```
​
3. Run Migrations & Seeders
Run the migrations and populate demo data (DatabaseSeeder & DemoSeeder):
```
php artisan migrate --seed
```
​
4. Build Assets & Start the Server
```
npm run dev
php artisan serve
```
​
### Running Tests
The repository includes feature and unit test suites powered by Pest covering authentication, admin CRUD, organizer management, payment authorization, and security integration:
```
php artisan test
```
​
Project Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/                  # Admin Dashboard, Users, Adventures, Bookings, Payments, Reviews
│   │   ├── Auth/                   # Breeze authentication controllers
│   │   ├── ActivityOrganizerController.php
│   │   ├── AdminOrganizerController.php
│   │   ├── AdminFraudReportController.php
│   │   ├── AdventureController.php
│   │   ├── AdventureSchedulesController.php
│   │   ├── AdventurerController.php
│   │   ├── BookingController.php
│   │   ├── ChatbotController.php
│   │   ├── CommunityController.php
│   │   ├── GuidesController.php
│   │   └── ReviewController.php
│   ├── Middleware/
│   │   ├── EnsureOrganizerIsApproved.php
│   │   └── EnsureUserIsAdmin.php
│   └── Requests/                   # Form request validation classes
├── Models/                         # Eloquent models for all core entities
├── Policies/                       # Adventure, Booking, and Payment authorization policies
└── Services/
    └── OllamaAdventureDayGenerator.php
database/
├── factories/
├── migrations/
└── seeders/
    ├── DatabaseSeeder.php
    └── DemoSeeder.php
lang/
└── ar.json                         # Arabic translations
resources/
├── css/pages/                      # Page-specific stylesheets & design tokens
├── js/components/                  # Modular UI, modal, and chatbot scripts
└── views/                          # Blade layouts, components, admin, organizer, and public views
tests/
└── Feature/                        # Pest feature & security integration tests
```
