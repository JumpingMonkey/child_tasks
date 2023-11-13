<?php

namespace Database\Seeders;

use App\Models\Adult;
use App\Models\AdultAccountSettings;
use App\Models\AdultType;
use App\Models\Child;
use App\Models\ChildReward;
use App\Models\ChildRewardImage;
use App\Models\GeneralAvailableRegularTask;
use App\Models\GeneralAvailableRegularTaskTemplate;
use App\Models\Image;
use App\Models\OneDayTask;
use App\Models\ProofType;
use App\Models\RegularTask;
use App\Models\RegularTaskTemplate;
use App\Models\Schedule;
use App\Models\TaskIcon;
use App\Models\TaskImage;
use App\Models\TaskProofImage;
use App\Models\Timer;
use App\Models\User;
use Illuminate\Support\Carbon;
use Database\Factories\ImageFactory;
use Database\Factories\TimerFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class ApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->me()->create();
        User::factory()->admin()->create();
        $proof = ProofType::factory()->state(new Sequence(
            ['title' => ProofType::PROOF_TYPES[0]],
            ['title' => ProofType::PROOF_TYPES[1]],
            // ['title' => ProofType::PROOF_TYPES[2]],
            // ['title' => ProofType::PROOF_TYPES[3]],
            // ['title' => ProofType::PROOF_TYPES[4]],
            // ['title' => ProofType::PROOF_TYPES[5]],
            // ['title' => ProofType::PROOF_TYPES[6]],
        ))
        ->count(2)
        ->create();

        $generalAvailableRegularTaskTemplate = [
            ['title' => ["en" => 'Arrange the toys', "ru" => "Собрать игрушки", "uk" => "Розкласти іграшки по місцях"],
            'description' => [
                "en" => "Put all the toys in their places and take a photo of this beauty.", 
                "ru" => "Разложи все игрушки по местам и сделай фото этой красоты.",
                "uk" => "Розклади усі іграшки по місцях і сфотографуй цю красу."
            ],
            'proof_type_id' => 1,
            'is_active' => true],

            ['title' => ["en" => "Make the bed", "ru" => "Заправить постель", "uk" => "Застелити ліжко"],
            'description' => [
                "en" => 'Take two photos: First photo - the bed is spread out. Second photo - the bed is made.',
                "ru" => "Сделай фото где ты красиво застелил постель.",
                "uk" => "Зроби фото де ти гарно застелив ліжко.",
            ],
            'proof_type_id' => 1,
            'is_active' => true],

            ['title' => ["en" => 'Brush your teeth', "ru" => "Почистить зубки", "uk" => "Почистити зуби",],
            'description' => [
                "en" => 'Brush your teeth for 2 minutes and then take a photo with a smile.',
                "ru" => 'Сделай фото себя в процессе чистки зубов и, конечно же, улыбайся.',
                "uk" => 'Зроби фотографію себе в процесі чищення зубів і, звичайно, посміхайся.'
            ],
            'proof_type_id' => 1,
            'is_active' => true],

            ['title' => ["en" => 'Clean the room', "ru" => "Убрать в комнате", "uk" => "Прибрати у своїй кімнаті",],
            'description' => [
                "en" => 'Dust surfaces, sweep the floor. Take a picture of this purity.',
                "ru" => 'Протри пыль, подмети пол. Сфотографируй эту чистоту.',
                "uk" => 'Протри пил, підміти підлогу. Сфотографуй цю чистоту.'
            ],
            'proof_type_id' => 1,
            'is_active' => true],

            ['title' => ["en" => 'Wash the dishes', "ru" => "Помыть посуду", "uk" => "Помити посуд",],
            'description' => [
                "en" => 'Take the photo of dirty and clean dishes.',
                "ru" => 'Сделай фото чистой посуды. ',
                "uk" => 'Зроби фотографію чистих тарілок.'
            ],
            'proof_type_id' => 1,
            'is_active' => true],

            ['title' => ["en" => 'Feed your pet', "ru" => "Покормить животное", "uk" => "Погодувати вихованця",],
            'description' => [
                "en" => 'Put food in a bowl of your pet and make photo of it.',
                "ru" => 'Положи еду в миску своего питомца и сделай её фото. ',
                "uk" => 'Поклади їжу в миску свого домашнього улюбленця і зроби фотографію.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Do exercises', "ru" => "Сделать зарядку", "uk" => "Зробити зарядку",],
            'description' => [
                "en" => 'Do warm-up exercises for 5 minutes, and at the end take a photo of yourself.',
                "ru" => 'Сделай 5 минутную зарядку. Разомни шею, плечи, ноги.',
                "uk" => 'Зроби 5-хвилинну зарядку. Розімні шию, плечі, ноги.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Read a book', "ru" => "Почитать книгу", "uk" => "Почитати книгу",],
            'description' => [
                "en" => 'Read a book and record a voice note about what you read.',
                "ru" => 'Почитай книгу и сфотографируй до какой страницы дочитал. ',
                "uk" => 'Почитай книгу та сфотографуй до якої сторінки дочитав.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Clean up after pet', "ru" => "Убрать за домашним животным", "uk" => "Прибрати за домашньою твариною",],
            'description' => [
                "en" => 'Cleen up after your pet and send a photo of his clean tray.',
                "ru" => 'Убери за своим питомцем и пришли фото его чистого лотка.',
                "uk" => 'Прибери за своїм домашнім улюбленцем і надішли фото його чистого горщика.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Dust surfaces', "ru" => "Вытереть пыль", "uk" => "Витерти пил",],
            'description' => [
                "en" => 'Wipe off the dust and take pictures of the cleanliness of the shelves.',
                "ru" => 'Протри пыль и сфотографируйте чистоту полок.',
                "uk" => 'Витри пил і сфотографуй чистоту полиць.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Do homework', "ru" => "Сделать домашнее задание", "uk" => "Зробити домашнє завдання",],
            'description' => [
                "en" => 'Take a photo of notebooks with completed homework.',
                "ru" => 'Сфотографируй тетради с выполненными домашними заданиями.',
                "uk" => 'Сфотографуй зошити з виконаними домашніми завданнями.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Water the flowers', "ru" => "Полить цветы", "uk" => "Полити квіти",],
            'description' => [
                "en" => 'Water are all the flowers in the house. But a lot of water is also bad. Take a photo of flower pots.',
                "ru" => 'Полей все цветы в доме, только аккуртно, не заливай их. Сильно много воды — это тоже плохо. Сделай фото 3-х вазонов.',
                "uk" => 'Полий усі квіти вдома, але акуратно, на заливай їх. Багато води — теж погано. Зроби фото вазонів.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Take out the trash', "ru" => "Вынести мусор", "uk" => "Винести мусор",],
            'description' => [
                "en" => 'Take a photo of an empty trash bin with a new trash bag in it.',
                "ru" => 'Сделай фото пустой мусорной корзины с новым мусорным пакетом в ней. ',
                "uk" => 'Зроби фотографію порожнього кошика для сміття з новим пакетом для сміття у ньому.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Copy a picture', "ru" => "Перерисовать рисунок", "uk" => "Перемалювати малюнок",],
            'description' => [
                "en" => 'Copy a picture that we showed and take photo of it.',
                "ru" => 'Перерисуй рисунок, который мы загадали и сделай его фото.',
                "uk" => 'Перемалюй малюнок, який ми загадали, і зробіть його фотографію.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Dress yourself', "ru" => "Одеться самому", "uk" => "Одягнутися самому",],
            'description' => [
                "en" => 'Dress yourself and take a photo in the mirror.',
                "ru" => 'Оденьтеся самостоятельно и сфотографируйся в зеркале.',
                "uk" => 'Одягнись самостійно і сфотографуйся в дзеркалі.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Vacuum the room', "ru" => "Попылисосить", "uk" => "Попилососити",],
            'description' => [
                "en" => 'Take a photo of the vacuum cleaner and the place where you vacuumed.',
                "ru" => 'Сфотографируй пылесос и место, где ты попылесосил.',
                "uk" => 'Сфотографуй пилосос і місце, де ти пилососив.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Sweep the floor', "ru" => "Подмести", "uk" => "Підмісти",],
            'description' => [
                "en" => 'Sweep up and take photos of as much trash as you can collect.',
                "ru" => 'Подмети и сфотографируй столько мусора, сколько удасться собрать.',
                "uk" => 'Підміти та фотографуй стільки сміття, скільки зможете зібрати.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],
            
            ['title' => ["en" => 'Get a good grade at school', "ru" => "Получить хорошую оценку", "uk" => "Отримати гарну оцінку",],
            'description' => [
                "en" => 'Take a photo of your grades in your diary.',
                "ru" => 'Сфотографируй свои оценки в дневнике.',
                "uk" => 'Сфотографуй свої оцінки у щоденнику.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Walk the dog', "ru" => "Выгулять собаку", "uk" => "Погуляти з собакою",],
            'description' => [
                "en" => 'Take a photo of a pet on the street, but let him have time to finish his affairs.',
                "ru" => 'Сделай фото питомца на улице, но позволь ему успеть закончить свои дела.',
                "uk" => 'Сфотографуй свого домашнього улюбленця на вулиці під час прогулянки.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en"  => 'Call grandparents', "ru" => "Позвонить бабушке", "uk" => "Зателефонувати бабусі",],
            'description' => [
                "en" => 'Call your grandmother and find out how she is doing.',
                "ru" => 'Позвони бабушке по телефону и спроси как её дела.',
                "uk" => 'Зателефонуй своїй бабусі і запитайте, як в неї справи.'
            ],
            'proof_type_id' => 2,
            'is_active' => false],

            ['title' => ["en" => 'Attend tranings', "ru" => "Посетить секцию", "uk" => "Відвідати секцію",],
            'description' => [
                "en" => 'Visit the training and take a photo of yourself there.',
                "ru" => 'Посетите тренировку и сфотографируй себя там.',
                "uk" => 'Відвідай гурток та зроби фото там.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Make a craft', "ru" => "Сделать поделку", "uk" => "Зробити виріб",],
            'description' => [
                "en" => 'Make a craft to your taste and take a photo of it.',
                "ru" => 'Сделай какую захочешь поделку и сфотографируй её.',
                "uk" => 'Зроби який забажаєш виріб і сфотографуй його.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Serve a table', "ru" => "Засервировать стол", "uk" => "Засервірувати стіл",],
            'description' => [
                "en" => 'Set the table, place plates, forks, spoons, glasses, napkins and take a photo.',
                "ru" => 'Накрой на стол, расставь тарелки, вилки, ложки, стаканы, салфетки и сделай фото.',
                "uk" => 'Накрий на стіл, розстав тарілки, виделки, ложки, склянки, серветки і зроби фото.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => 'Play with younger', "ru" => "Поиграть с младшим", "uk" => "Пограти з молодшим",],
            'description' => [
                "en" => 'Ask your brother what game he would like to play with you today. Take a photo while playing.',
                "ru" => 'Спроси у брата, в какую игру он хотел бы поиграть с тобой. В процессе игры сделайте совместное фото.',
                "uk" => 'Запитай малечу, в яку гру вона хотіла би пограти з тобою. Під час гри зробіть спільну фотографію.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],

            ['title' => ["en" => "Find an item", "ru" => "Найди предмет", "uk" => "Знайти рiч",],
            'description' => [
                "en" => 'Find this object at house and make photo of it.',
                "ru" => 'Найди этот предмет у себя дома и сделай его фото.',
                "uk" => 'Знайди цей предмет у себе вдома та зроби його фотографію.'
            ],
            'proof_type_id' => 1,
            'is_active' => false],
        ];

        $taskIcon = TaskIcon::factory()->create();

        $schedule = Schedule::factory()->state([
            'monday' => 1,
            'tuesday' => 1,
            'wednesday' => 1,
            'thursday' => 1,
            'friday' => 1,
            'saturday' => 1,
            'sunday' => 1,
        ])->create();

        foreach($generalAvailableRegularTaskTemplate as $task){        
            
            GeneralAvailableRegularTaskTemplate::factory()
            ->for($schedule)
            ->has(TaskImage::factory(), 'image')
            // ->for($taskIcon, 'taskIcon')
            ->create($task);
        }
        
        $adultTypes = AdultType::factory()
            ->state(new Sequence(
                ['title' => ["en" => 'Mother', "ru" => "Мама", "uk" => "Мати"]],
                ['title' => ["en" => 'Father', "ru" => "Папа", "uk" => "Тато"]],
                ['title' => ["en" => 'Grandmother', "ru" => "Бабушка", "uk" => "Бабуся"]],
                ['title' => ["en" => 'Grandfather', "ru" => "Дедушка", "uk" => "Дідусь"]],
                ['title' => ["en" => 'Sister', "ru" => "Сестра", "uk" => "Сестра"]],
                ['title' => ["en" => 'Brother', "ru" => "Брат", "uk" => "Брат"]],
                ['title' => ["en" => 'Uncle', "ru" => "Дядя", "uk" => "Дядько"]],
                ['title' => ["en" => 'Aunt', "ru" => "Тетя", "uk" => "Тітка"]],
                ['title' => ["en" => 'Nunny', "ru" => "Няня", "uk" => "Няня"]],
                ['title' => ["en" => 'etc.', "ru" => "Другое", "uk" => "Іньше"]],
            ))->count(10)->create();

        $j = 0;
        while($j < 10){
            $child = Child::factory()->create();
            $prem = fake()->randomElement([true, false]);
            $premUntil = $prem ? Carbon::now()->addDays(30)->toDateTimeString() : null;
            
            $adult = Adult::factory()
                ->hasAttached($child)
                ->has(AdultAccountSettings::factory(), 'accountSettings')
                ->for($adultTypes->random())
                ->create(['is_premium' => $prem, 'until' => $premUntil]);
                
                $proofTypeForOneDayTask = $proof->random();
                
                    OneDayTask::factory()
                    ->for($proofTypeForOneDayTask)
                    ->for($child)
                    ->for($adult)
                    // ->for($taskIcon, 'icon')
                    ->has(TaskProofImage::factory(), 'imageProof')
                    ->has(TaskImage::factory(), 'image')
                    ->create();
            
            $generalAvailableRegTaskTemp = 
                GeneralAvailableRegularTaskTemplate::where('is_active', true)->get();
                $activeTaskCounter = 1;
            foreach($generalAvailableRegTaskTemp as $task){
                
                $attributes = $task->getAttributes();
                $attributes['title'] = $task->title;
                $attributes['description'] = $task->description;
                unset($attributes['created_at'], $attributes['updated_at'], $attributes['id']);
                
                if($activeTaskCounter < 4) {
                    $attributes['is_active'] = 1;
                    $activeTaskCounter++;
                } else {
                    $attributes['is_active'] = 0;
                }
                $attributes['is_general_available'] = true;

                $taskTemplate = RegularTaskTemplate::factory()
                ->has(TaskImage::factory(), 'image')
                ->for($child)
                ->for($adult)
                // ->for($taskIcon, 'icon')
                ->create($attributes);

                $curentWeekDay = Str::lower(Carbon::now()->englishDayOfWeek);

                
                if($taskTemplate->is_active && 
                    $taskTemplate->schedule->$curentWeekDay){
                    RegularTask::factory()
                    ->for($taskTemplate)
                    ->create();  
                }
            
                // GeneralAvailableRegularTaskTemplate::factory()
                // ->for(Schedule::factory()->state([
                //     'monday' => 1,
                //     'tuesday' => 1,
                //     'wednesday' => 1,
                //     'thursday' => 1,
                //     'friday' => 1,
                //     'saturday' => 1,
                //     'sunday' => 1,
                // ]))
                // ->has(TaskImage::factory(), 'image')
                // ->for($taskIcon, 'taskIcon')
                
                // ->create($task);
            }
            

            ChildReward::factory()
                ->for($adult)
                ->for($child)
                ->hasImage()
                ->create();
                
            $j++;
        }
    }
}
