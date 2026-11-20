# YallaJO — Shared Frontend Guide

> **النطاق:** Laravel 12 + Blade + Tailwind CSS 4 + Vite. هذا الدليل يشرح واجهات YallaJO المشتركة ولا يغيّر Backend أو صلاحيات المستخدمين.
>
> **الحالة:** الهيكل المشترك اجتاز اختبارات Blade وVite ومعاينات المتصفح الأساسية بحسب نتائج التطوير. بعض الصفحات وروابطها وبياناتها ما تزال تجريبية أو غير موصولة بالـ Backend.

## 1. الفكرة الأساسية

لا تكرر `<!DOCTYPE html>` أو Navbar أو Footer أو Sidebar في صفحاتك. اختر Layout المناسبة، ثم اكتب محتوى الصفحة فقط. توجد أربعة ملفات رئيسية:

| Layout | الاستخدام | ملاحظات |
| --- | --- | --- |
| `layouts.app` | أساس HTML وRTL وVite وMeta Tags | لا تُستخدم مباشرة لصفحات الأعمال غالبًا |
| `layouts.site` | Home وAdventures وReviews وBookings | تتضمن Navbar وFooter |
| `layouts.dashboard` | Admin وActivity Organizer | Sidebar وHeader وUser Area مشتركة؛ Theme للمظهر فقط |
| `layouts.auth` | Login وRegister وForgot Password | واجهة مبسطة للنماذج |

## 2. المسارات والمجلدات

```text
resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   ├── site.blade.php
│   │   ├── dashboard.blade.php
│   │   └── auth.blade.php
│   ├── partials/
│   │   ├── site/{navbar,footer}.blade.php
│   │   ├── dashboard/{sidebar,header,user-area,mobile-sidebar}.blade.php
│   │   └── home/featured-adventures.blade.php
│   ├── components/
│   │   ├── ui/{button,card,input,modal}.blade.php
│   │   └── adventure/{search-bar,card}.blade.php
│   ├── pages/{home,ui-preview,dashboard-preview,auth-preview}.blade.php
│   ├── adventurers/
│   ├── reviews/
│   ├── organizers/
│   └── admin/
├── css/{app,tokens}.css
└── js/
    ├── app.js
    └── components/{modal,dashboard-mobile-sidebar}.js
```

**تنبيه:** `home-preview` اسم Route محلية تعرض View باسم `pages.home`، وليست ملف View مستقلًا. صفحات المعاينة للتطوير المحلي فقط. تحقق من أسماء الملفات في نسختك قبل أي تعديل.

## 3. إنشاء صفحة جديدة

**صفحة عامة:**

```blade
@extends('layouts.site')

@section('title', 'تفاصيل المغامرة | YallaJO')

@section('content')
    <h1>تفاصيل المغامرة</h1>
@endsection
```

**صفحة لوحة منظّم:**

```blade
@extends('layouts.dashboard')

@section('dashboard-theme', 'organizer')
@section('dashboard-name', 'بوابة المنظمين')
@section('dashboard-title', 'مغامراتي')

@section('content')
    <h2>مغامراتي</h2>
@endsection
```

**صفحة مسؤول:** استخدم `layouts.dashboard` أيضًا، لكن غيّر `dashboard-theme` إلى `admin`، وحدد العنوان. هذه القيمة خاصة بالتصميم **وليست Authorization**.

**صفحة مصادقة:**

```blade
@extends('layouts.auth')

@section('title', 'تسجيل الدخول | YallaJO')
@section('auth-heading', 'تسجيل الدخول')

@section('content')
    {{-- نموذج تسجيل الدخول الخاص بالوحدة --}}
@endsection
```

## 4. استخدام Blade Components

```blade
<x-ui.button type="submit" variant="primary">حفظ</x-ui.button>
<x-ui.button type="button" variant="outline">إلغاء</x-ui.button>

<x-ui.card variant="outline" padding="md">
    <h2>ملخص الحجز</h2>
</x-ui.card>

<x-ui.input
    name="email"
    label="البريد الإلكتروني"
    type="email"
    autocomplete="email"
    required
/>
```

- `button`: لتنفيذ إجراء؛ استخدم `<a>` للرابط الذي ينقل المستخدم إلى صفحة أخرى.
- `card`: حاوية مرئية عامة؛ لا تضع بيانات مغامرة ثابتة داخل المكوّن العام.
- `input`: يدعم Label وOld Input وValidation Errors وError Bag. استخدم `id` مختلفًا إذا تكرر النموذج في الصفحة.
- `modal`: لعمليات Create وEdit القصيرة. صفحات Show كاملة؛ العمليات الطويلة مثل إنشاء المغامرات والحجز والدفع يفضّل أن تكون صفحات مستقلة.
- `adventure.card`: بطاقة عرض مغامرة؛ يجب أن تأتي بيانات المغامرات الحقيقية من Controller، وليس من مصفوفة تجريبية في الإنتاج.

## 5. نموذج Modal

```blade
<x-ui.button type="button" data-modal-open="create-review">
    إضافة تقييم
</x-ui.button>

<x-ui.modal name="create-review" title="إضافة تقييم" error-bag="createReview">
    <form method="POST" action="{{ route('reviews.store', $adventure) }}">
        @csrf
        <input type="hidden" name="_modal" value="create-review">
        {{-- حقول الحجز والتقييم والتعليق مطلوبة حسب قواعد Controller --}}
        <x-ui.button type="submit">حفظ</x-ui.button>
    </form>
</x-ui.modal>
```

هذا المثال **غير مكتمل وظيفيًا** ويشرح البنية فقط. على مالك الميزة توحيد اسم الـ Error Bag بين Validation داخل Controller وProps للمكوّنات، ومعالجة إعادة فتح النافذة عند فشل الطلب، والتحقق من CSRF وAuthorization على الخادم. أبقِ `create.blade.php` و`edit.blade.php` متاحتين كرابط مباشر أو fallback عند الحاجة.

## 6. Design System & CSS

- التعديل المركزي للألوان والخطوط في `resources/css/tokens.css`.
- تحميل التصميم من `resources/css/app.css` عبر Vite.
- ملفات تفاعل JavaScript داخل `resources/js/components/`، ويُستورد ما يلزم من `resources/js/app.js`.
- تجنّب Inline Styles وتكرار Navbar وFooter وSidebar.
- اتبع تصميم RTL، وحافظ على حقول البريد والأرقام عند الحاجة بتنسيق LTR.
- حافظ على استجابة الهواتف وإمكانية التنقل بلوحة المفاتيح ورسائل الأخطاء الواضحة.

## 7. Routes & Authentication

- استخدم **Named Routes** مثل `route('reviews.index', $adventure)`، ولا تكتب عناوين ثابتة لمواقع حقيقية.
- بعض أسماء Routes في Sidebar مقترحة وقد لا تكون موجودة. اتفق مع أصحاب وحدات Admin وOrganizer على الأسماء النهائية قبل إزالة حالات «قريبًا».
- `Route::has()` يمنع إنشاء رابط غير موجود، لكنه لا يعني أن الصفحة جاهزة أو أن المستخدم يملك صلاحية دخولها.
- حَماية المسارات وعمليات الحفظ تتم بواسطة Middleware وPolicies/Authorization في Backend، وليس بواسطة `dashboard-theme` أو إخفاء عناصر الواجهة.
- صفحات `ui-preview` و`home-preview` و`dashboard-preview` و`auth-preview` للتطوير المحلي فقط؛ لا تجعلها صفحات عامة في الإنتاج.
- صور Stitch والبيانات والأسعار وأزرار الحجز والبحث المعطّلة هي عناصر معاينة حتى يجهز الربط الفعلي.

## 8. ملكية الملفات وقواعد الفريق

| المسؤول | نطاق الملفات |
| --- | --- |
| Frontend/Layout owner | `resources/views/layouts/`, `partials/site/`, `partials/dashboard/`, `components/ui/`, `resources/css/tokens.css`, `resources/js/components/` |
| Adventures owner | `resources/views/adventures/`، والمكونات المتخصصة بعد الاتفاق |
| Adventurer & Reviews owner | `resources/views/adventurers/` و`resources/views/reviews/` |
| Organizer owner | `resources/views/organizers/` |
| Admin owner | `resources/views/admin/` |
| Authentication owner | `resources/views/auth/` |

هذه ملكيات تنظيمية وليست قيودًا تقنية. **أي تعديل لملف مشترك يحتاج Pull Request ومراجعة من مالك الواجهة**. لا تغيّر Controllers أو Models أو Migrations تخص زميلًا ضمن PR الواجهات.

## 9. Naming Conventions

- Blade filenames: `kebab-case.blade.php` للمكونات و`index/create/show/edit.blade.php` لصفحات CRUD.
- أسماء مجلدات الوحدات متسقة مع Views التي تتوقعها Controllers؛ مثال: `view('adventurers.show')` ↔ `resources/views/adventurers/show.blade.php`.
- Blade Components: `x-ui.button` ↔ `components/ui/button.blade.php`.
- JavaScript: أسماء واضحة مثل `dashboard-mobile-sidebar.js`.
- تجنب حذف أو تغيير أسماء Routes وSections دون الاتفاق مع الفريق.

## 10. Checklist قبل Pull Request

- [ ] فحص `git status --short` للتأكد من أن التغييرات المتوقعة فقط موجودة.
- [ ] نجاح `php artisan view:cache`.
- [ ] نجاح `npm.cmd run build` على Windows.
- [ ] نجاح `node --check` لملفات JavaScript المعدّلة.
- [ ] اختبار Desktop وMobile وRTL والتنقل بلوحة المفاتيح.
- [ ] التأكد من ظهور Validation Errors وOld Input عند النماذج الحقيقية (ليس المعاينات فقط).
- [ ] عدم إضافة `.env`, `vendor/`, `node_modules/`, `public/build/` أو بيانات شخصية.
- [ ] عدم تفعيل زر أو رابط وظيفة لم تُنفذ بعد.
- [ ] عدم تعديل Backend أو Database في PR الواجهات.
- [ ] عدم وجود أخطاء مسافات أو دمج وفق `git diff --check`.

## 11. قواعد Git وPull Requests

1. اعمل على Branch مخصصة، مثل `feat/shared-layout`. لا تعمل مباشرة على `main`.
2. قبل الدمج، افحص الملفات المتغيرة وتأكد أن Pull Request لا تضم تغييرات من وحدات أخرى.
3. استخدم Commits صغيرة وواضحة، مثل `feat(frontend): add shared Blade layouts`.
4. افتح PR نحو الفرع الأساسي المتفق عليه، وأضف Screenshots للكمبيوتر والهاتف، وشرح الملفات، ونتائج الاختبارات.
5. اطلب Review من مالك Layout وصاحب الوحدة التي تأثرت، ولا تدمج قبل الموافقة وحل التعارضات.
6. لا تستخدم Force Push على `main`، ولا تنفذ Migrations حذف بيانات ضمن PR واجهات.
7. بعد الدمج، يسحب أعضاء الفريق التحديثات ويبنون صفحاتهم فوق Layout الموجودة.

## 12. مراجع

- Laravel 12 Blade: https://laravel.com/docs/12.x/blade
- Laravel 12 Vite: https://laravel.com/docs/12.x/vite
- GitHub Protected Branches: https://docs.github.com/en/repositories/configuring-branches-and-merges-in-your-repository/managing-protected-branches/about-protected-branches

**الهدف من الدليل:** تسليم بنية Frontend قابلة لإعادة الاستخدام. لا يعني إكمال كل صفحات المنصة أو Backend أو اختبار تكاملها النهائي.
