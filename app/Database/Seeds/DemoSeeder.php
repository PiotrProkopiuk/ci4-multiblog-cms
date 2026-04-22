<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $blogs = [
            'tailo' => [
                'name' => 'Tailo',
                'domain' => 'tailo.eu',
                'default_language' => 'pl',
                'post_title' => 'Jak przygotować dom na nowego psa lub kota',
                'post_content' => '<p>Praktyczna lista rzeczy, które warto przygotować przed pojawieniem się zwierzaka w domu: spokojne miejsce, miski, karma, opieka weterynaryjna i bezpieczne rytuały.</p>',
                'translations' => [
                    'pl' => [
                        'nav.login' => 'Logowanie',
                        'hero.eyebrow' => 'Domowe zwierzęta bez chaosu',
                        'hero.title' => 'Mądre poradniki dla opiekunów psów, kotów i małych pupili',
                        'hero.subtitle' => 'Tailo pomaga ogarniać codzienną opiekę, zdrowie, karmienie i dobre nawyki zwierząt domowych prostym językiem.',
                        'hero.cta' => 'Czytaj najnowsze wpisy',
                        'feature.one.title' => 'Opieka krok po kroku',
                        'feature.one.text' => 'Praktyczne wskazówki dla początkujących i bardziej doświadczonych opiekunów.',
                        'feature.two.title' => 'Zdrowie i zachowanie',
                        'feature.two.text' => 'Objawy, rutyny i codzienne decyzje opisane bez przesadnego komplikowania.',
                        'feature.three.title' => 'Dom przyjazny pupilom',
                        'feature.three.text' => 'Pomysły na bezpieczną, spokojną i wygodną przestrzeń dla zwierząt.',
                        'posts.heading' => 'Najnowsze poradniki',
                        'posts.read_more' => 'Czytaj dalej',
                        'posts.empty' => 'Brak opublikowanych wpisów w tym języku.',
                    ],
                    'en' => [
                        'nav.login' => 'Login',
                        'hero.eyebrow' => 'Pets made simple',
                        'hero.title' => 'Smart guides for dog, cat and small pet owners',
                        'hero.subtitle' => 'Tailo helps with everyday care, health, feeding and calm routines for pets.',
                        'hero.cta' => 'Read latest posts',
                        'feature.one.title' => 'Care step by step',
                        'feature.one.text' => 'Practical advice for new and experienced pet owners.',
                        'feature.two.title' => 'Health and behavior',
                        'feature.two.text' => 'Everyday decisions explained clearly and calmly.',
                        'feature.three.title' => 'Pet-friendly home',
                        'feature.three.text' => 'Ideas for safe and comfortable living with animals.',
                        'posts.heading' => 'Latest guides',
                        'posts.read_more' => 'Read more',
                        'posts.empty' => 'No published posts for this language yet.',
                    ],
                    'de' => [
                        'nav.login' => 'Login',
                        'hero.eyebrow' => 'Haustiere einfach erklärt',
                        'hero.title' => 'Praktische Ratgeber für Hunde, Katzen und kleine Haustiere',
                        'hero.subtitle' => 'Tailo hilft bei Pflege, Gesundheit, Fütterung und ruhigen Routinen im Alltag.',
                        'hero.cta' => 'Neueste Beiträge lesen',
                        'feature.one.title' => 'Pflege Schritt für Schritt',
                        'feature.one.text' => 'Praktische Tipps für neue und erfahrene Tierhalter.',
                        'feature.two.title' => 'Gesundheit und Verhalten',
                        'feature.two.text' => 'Alltägliche Entscheidungen klar und ruhig erklärt.',
                        'feature.three.title' => 'Tierfreundliches Zuhause',
                        'feature.three.text' => 'Ideen für ein sicheres und gemütliches Leben mit Tieren.',
                        'posts.heading' => 'Neueste Ratgeber',
                        'posts.read_more' => 'Weiterlesen',
                        'posts.empty' => 'Noch keine veröffentlichten Beiträge in dieser Sprache.',
                    ],
                ],
            ],
            'gardenhaven' => [
                'name' => 'Garden Haven',
                'domain' => 'gardenhaven.eu',
                'default_language' => 'pl',
                'post_title' => 'Mały ogród, duży efekt: od czego zacząć sezon',
                'post_content' => '<p>Nawet niewielka przestrzeń może wyglądać świeżo i przytulnie. Zacznij od gleby, prostego planu nasadzeń i kilku miejsc, które będą cieszyć oko przez cały sezon.</p>',
                'translations' => [
                    'pl' => [
                        'nav.login' => 'Logowanie',
                        'hero.eyebrow' => 'Dom i ogród z charakterem',
                        'hero.title' => 'Inspiracje dla pięknego domu, spokojnego ogrodu i lepszej codzienności',
                        'hero.subtitle' => 'Garden Haven zbiera praktyczne pomysły na wnętrza, rośliny, sezonowe prace i przytulne miejsca do życia.',
                        'hero.cta' => 'Zobacz najnowsze inspiracje',
                        'feature.one.title' => 'Ogród sezon po sezonie',
                        'feature.one.text' => 'Co sadzić, kiedy przycinać i jak planować prace bez stresu.',
                        'feature.two.title' => 'Domowe metamorfozy',
                        'feature.two.text' => 'Małe zmiany, które robią dużą różnicę w mieszkaniu i domu.',
                        'feature.three.title' => 'Praktyczne listy',
                        'feature.three.text' => 'Czytelne checklisty i pomysły gotowe do wdrożenia w weekend.',
                        'posts.heading' => 'Najnowsze wpisy',
                        'posts.read_more' => 'Czytaj dalej',
                        'posts.empty' => 'Brak opublikowanych wpisów w tym języku.',
                    ],
                    'en' => [
                        'nav.login' => 'Login',
                        'hero.eyebrow' => 'Home and garden with soul',
                        'hero.title' => 'Ideas for a beautiful home, calmer garden and better everyday life',
                        'hero.subtitle' => 'Garden Haven collects practical ideas for interiors, plants, seasonal work and cozy living.',
                        'hero.cta' => 'See latest inspiration',
                        'feature.one.title' => 'Garden season by season',
                        'feature.one.text' => 'What to plant, when to prune and how to plan work calmly.',
                        'feature.two.title' => 'Home refreshes',
                        'feature.two.text' => 'Small changes that make a real difference indoors.',
                        'feature.three.title' => 'Practical lists',
                        'feature.three.text' => 'Readable checklists and ideas ready for the weekend.',
                        'posts.heading' => 'Latest posts',
                        'posts.read_more' => 'Read more',
                        'posts.empty' => 'No published posts for this language yet.',
                    ],
                    'de' => [
                        'nav.login' => 'Login',
                        'hero.eyebrow' => 'Haus und Garten mit Charakter',
                        'hero.title' => 'Inspiration für ein schönes Zuhause und einen ruhigen Garten',
                        'hero.subtitle' => 'Garden Haven sammelt praktische Ideen für Räume, Pflanzen, Saisonarbeit und gemütliches Wohnen.',
                        'hero.cta' => 'Neueste Inspiration ansehen',
                        'feature.one.title' => 'Garten Saison für Saison',
                        'feature.one.text' => 'Was pflanzen, wann schneiden und wie man Arbeit ruhig plant.',
                        'feature.two.title' => 'Zuhause auffrischen',
                        'feature.two.text' => 'Kleine Änderungen mit großer Wirkung im Haus.',
                        'feature.three.title' => 'Praktische Listen',
                        'feature.three.text' => 'Klare Checklisten und Ideen für das Wochenende.',
                        'posts.heading' => 'Neueste Beiträge',
                        'posts.read_more' => 'Weiterlesen',
                        'posts.empty' => 'Noch keine veröffentlichten Beiträge in dieser Sprache.',
                    ],
                ],
            ],
            'zenvitality' => [
                'name' => 'Zen Vitality',
                'domain' => 'zenvitality.eu',
                'default_language' => 'pl',
                'post_title' => 'Poranna rutyna, która naprawdę da się utrzymać',
                'post_content' => '<p>Dobra rutyna nie musi być długa. Kilka minut ruchu, szklanka wody, chwila oddechu i prosty plan dnia potrafią zmienić energię całego poranka.</p>',
                'translations' => [
                    'pl' => [
                        'nav.login' => 'Logowanie',
                        'hero.eyebrow' => 'Zdrowie, uroda i spokojna energia',
                        'hero.title' => 'Lekki przewodnik po zdrowiu, pielęgnacji i codziennym dobrostanie',
                        'hero.subtitle' => 'Zen Vitality łączy praktyczne rytuały, urodę i zdrowe nawyki w spokojne treści, do których chce się wracać.',
                        'hero.cta' => 'Odkryj najnowsze artykuły',
                        'feature.one.title' => 'Rytuały zdrowia',
                        'feature.one.text' => 'Proste nawyki, które łatwo włączyć do prawdziwego życia.',
                        'feature.two.title' => 'Uroda bez presji',
                        'feature.two.text' => 'Pielęgnacja, regeneracja i dobre samopoczucie bez przesady.',
                        'feature.three.title' => 'Spokojny balans',
                        'feature.three.text' => 'Treści o energii, odpoczynku i codziennym rytmie.',
                        'posts.heading' => 'Najnowsze artykuły',
                        'posts.read_more' => 'Czytaj dalej',
                        'posts.empty' => 'Brak opublikowanych wpisów w tym języku.',
                    ],
                    'en' => [
                        'nav.login' => 'Login',
                        'hero.eyebrow' => 'Health, beauty and calm energy',
                        'hero.title' => 'A light guide to wellness, beauty and everyday vitality',
                        'hero.subtitle' => 'Zen Vitality blends practical rituals, beauty and healthy habits into calm content worth returning to.',
                        'hero.cta' => 'Explore latest articles',
                        'feature.one.title' => 'Healthy rituals',
                        'feature.one.text' => 'Simple habits that fit real life.',
                        'feature.two.title' => 'Beauty without pressure',
                        'feature.two.text' => 'Care, recovery and wellbeing without overcomplication.',
                        'feature.three.title' => 'Calm balance',
                        'feature.three.text' => 'Content about energy, rest and daily rhythm.',
                        'posts.heading' => 'Latest articles',
                        'posts.read_more' => 'Read more',
                        'posts.empty' => 'No published posts for this language yet.',
                    ],
                    'de' => [
                        'nav.login' => 'Login',
                        'hero.eyebrow' => 'Gesundheit, Beauty und ruhige Energie',
                        'hero.title' => 'Ein leichter Guide für Wellness, Pflege und tägliche Vitalität',
                        'hero.subtitle' => 'Zen Vitality verbindet praktische Rituale, Schönheit und gesunde Gewohnheiten zu ruhigen Inhalten.',
                        'hero.cta' => 'Neueste Artikel entdecken',
                        'feature.one.title' => 'Gesunde Rituale',
                        'feature.one.text' => 'Einfache Gewohnheiten, die in echtes Leben passen.',
                        'feature.two.title' => 'Beauty ohne Druck',
                        'feature.two.text' => 'Pflege, Erholung und Wohlbefinden ohne Übertreibung.',
                        'feature.three.title' => 'Ruhige Balance',
                        'feature.three.text' => 'Inhalte über Energie, Ruhe und täglichen Rhythmus.',
                        'posts.heading' => 'Neueste Artikel',
                        'posts.read_more' => 'Weiterlesen',
                        'posts.empty' => 'Noch keine veröffentlichten Beiträge in dieser Sprache.',
                    ],
                ],
            ],
        ];

        foreach ($blogs as $slug => $data) {
            $blog = $this->db->table('blogs')->where('slug', $slug)->get()->getRowArray();

            if (! $blog) {
                $this->db->table('blogs')->insert([
                    'name' => $data['name'],
                    'slug' => $slug,
                    'domain' => $data['domain'],
                    'default_language' => $data['default_language'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $blog = $this->db->table('blogs')->where('slug', $slug)->get()->getRowArray();
            } else {
                $this->db->table('blogs')->where('id', $blog['id'])->update([
                    'name' => $data['name'],
                    'domain' => $data['domain'],
                    'default_language' => $data['default_language'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $blogId = $blog['id'];
            $user = $this->db->table('users')
                ->where('blog_id', $blogId)
                ->where('email', 'admin@example.com')
                ->get()
                ->getRowArray();

            if (! $user) {
                $this->db->table('users')->insert([
                    'blog_id' => $blogId,
                    'email' => 'admin@example.com',
                    'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                    'name' => 'Admin',
                    'role' => 'admin',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $user = $this->db->table('users')
                    ->where('blog_id', $blogId)
                    ->where('email', 'admin@example.com')
                    ->get()
                    ->getRowArray();
            }

            if ($this->db->table('posts')->where('blog_id', $blogId)->countAllResults() === 0) {
                $this->db->table('posts')->insert([
                    'blog_id' => $blogId,
                    'user_id' => $user['id'] ?? null,
                    'title' => $data['post_title'],
                    'content' => $data['post_content'],
                    'status' => 'publish',
                    'language' => 'pl',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            foreach ($data['translations'] as $language => $translations) {
                foreach ($translations as $key => $value) {
                    $existing = $this->db->table('translations')
                        ->where('blog_id', $blogId)
                        ->where('language', $language)
                        ->where('translation_key', $key)
                        ->get()
                        ->getRowArray();

                    if ($existing) {
                        continue;
                    }

                    $this->db->table('translations')->insert([
                        'blog_id' => $blogId,
                        'language' => $language,
                        'translation_key' => $key,
                        'value' => $value,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
    }
}
