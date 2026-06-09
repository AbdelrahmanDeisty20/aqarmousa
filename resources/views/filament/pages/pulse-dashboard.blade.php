<x-filament-panels::page>
    <div x-data="{ open: true }" class="mb-6">
        <!-- Banner Header -->
        <div style="position: relative; overflow: hidden; border-radius: 1rem; background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(99, 102, 241, 0.08) 50%, rgba(168, 85, 247, 0.08) 100%); border: 1px solid rgba(99, 102, 241, 0.2); padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); transition: all 0.3s ease-in-out;">
            <!-- Background decorative shapes -->
            <div style="position: absolute; right: -2.5rem; top: -2.5rem; width: 10rem; height: 10rem; background-color: rgba(99, 102, 241, 0.08); border-radius: 9999px; filter: blur(24px);"></div>
            <div style="position: absolute; left: -2.5rem; bottom: -2.5rem; width: 10rem; height: 10rem; background-color: rgba(59, 130, 246, 0.08); border-radius: 9999px; filter: blur(24px);"></div>

            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div style="padding: 0.75rem; background-color: rgba(255, 255, 255, 0.9); border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid rgba(99, 102, 241, 0.15); color: #4f46e5; display: flex; align-items: center; justify-content: center;">
                        <svg class="animate-pulse" style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white" style="margin: 0;">
                            {{ app()->getLocale() === 'ar' ? 'دليل شاشة مراقبة أداء الموقع' : 'Site Performance Dashboard Guide' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" style="margin: 0; padding-top: 0.25rem;">
                            {{ app()->getLocale() === 'ar' ? 'تعرف على كيفية قراءة مؤشرات الأداء الحية لموقعك والمحافظة على استقراره.' : 'Learn how to read real-time performance indicators and maintain site stability.' }}
                        </p>
                    </div>
                </div>

                <button @click="open = !open" style="display: flex; align-items: center; justify-content: center; padding: 0.5rem; border-radius: 0.5rem; background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(229, 231, 235, 1); cursor: pointer; transition: background-color 0.2s;" class="hover:bg-white dark:bg-gray-800/80 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400">
                    <svg class="transition-transform duration-300" :class="{ 'rotate-180': !open }" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <!-- Expandable Content -->
            <div x-show="open" x-collapse x-transition style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(99, 102, 241, 0.15);" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1: Cache -->
                <div style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(4px); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(229, 231, 235, 0.8); transition: all 0.3s;" class="dark:bg-gray-800/40 dark:border-gray-700/60 hover:border-indigo-500/30">
                    <div class="flex items-center gap-3 mb-2">
                        <span style="padding: 0.5rem; border-radius: 0.5rem; background-color: rgba(16, 185, 129, 0.1); color: #059669; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.5 8.5C16.8 5.2 19 3 19 3S16.8 5.2 13.5 6.5M15.5 8.5L12 12M13.5 6.5C10.5 7.8 7 11 7 15L3 19L5 21L9 17C13 17 16.2 13.5 17.5 10.5M13.5 6.5L10.5 9.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17L7 19M15 9L17 7M9.5 14.5L7.5 16.5" />
                            </svg>
                        </span>
                        <h4 class="font-semibold text-gray-900 dark:text-white" style="margin: 0;">
                            {{ app()->getLocale() === 'ar' ? 'التخزين المؤقت (Cache)' : 'Cache Performance' }}
                        </h4>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400" style="margin: 0;">
                        {{ app()->getLocale() === 'ar' 
                            ? 'يعرض سرعة استجابة الموقع. كلما زادت نسبة الـ (Hits) يعني أن الموقع سريع ويجلب البيانات من الذاكرة الفائقة دون استهلاك السيرفر وقاعدة البيانات.' 
                            : 'Displays how fast your site responds. A high Hit Rate means the site serves data from fast memory without putting load on the database.' }}
                    </p>
                </div>

                <!-- Card 2: Queues -->
                <div style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(4px); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(229, 231, 235, 0.8); transition: all 0.3s;" class="dark:bg-gray-800/40 dark:border-gray-700/60 hover:border-indigo-500/30">
                    <div class="flex items-center gap-3 mb-2">
                        <span style="padding: 0.5rem; border-radius: 0.5rem; background-color: rgba(99, 102, 241, 0.1); color: #4f46e5; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6c0-1.657 3.582-3 8-3s8 1.343 8 3M4 6c0 1.657 3.582 3 8 3s8-1.343 8-3M4 6v4c0 1.657 3.582 3 8 3s8-1.343 8-3V6" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 10v4c0 1.657 3.582 3 8 3s8-1.343 8-3v-4" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 14v4c0 1.657 3.582 3 8 3s8-1.343 8-3v-4" />
                            </svg>
                        </span>
                        <h4 class="font-semibold text-gray-900 dark:text-white" style="margin: 0;">
                            {{ app()->getLocale() === 'ar' ? 'طوابير العمل (Queues)' : 'Background Queues' }}
                        </h4>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400" style="margin: 0;">
                        {{ app()->getLocale() === 'ar' 
                            ? 'لمراقبة المهام الخلفية كإرسال الإيميلات والإشعارات. تأكد دائماً أن قسم (Failed) لا يحتوي على أخطاء حمراء لضمان وصول الرسائل.' 
                            : 'Monitors background jobs like emails and notifications. Make sure the (Failed) section is empty to ensure message delivery.' }}
                    </p>
                </div>

                <!-- Card 3: Usage -->
                <div style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(4px); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(229, 231, 235, 0.8); transition: all 0.3s;" class="dark:bg-gray-800/40 dark:border-gray-700/60 hover:border-indigo-500/30">
                    <div class="flex items-center gap-3 mb-2">
                        <span style="padding: 0.5rem; border-radius: 0.5rem; background-color: rgba(168, 85, 247, 0.1); color: #9333ea; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 7.97 5.132-2.51.52.012 3.123z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 11.517 1.297l-.041.02-.04-.02a.75.75 0 11-.517-1.296l.04-.02z" />
                            </svg>
                        </span>
                        <h4 class="font-semibold text-gray-900 dark:text-white" style="margin: 0;">
                            {{ app()->getLocale() === 'ar' ? 'نشاط المستخدمين (Usage)' : 'Application Usage' }}
                        </h4>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400" style="margin: 0;">
                        {{ app()->getLocale() === 'ar' 
                            ? 'يكشف لك أكثر المستخدمين أو الحسابات نشاطاً وطلباً لصفحات الموقع خلال الساعة الماضية، مما يساعد على كشف أي هجمات أو ضغط غير طبيعي.' 
                            : 'Reveals the most active users or accounts requesting site pages over the last hour, helping spot any attacks or unusual load.' }}
                    </p>
                </div>

                <!-- Card 4: Servers -->
                <div style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(4px); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(229, 231, 235, 0.8); transition: all 0.3s;" class="dark:bg-gray-800/40 dark:border-gray-700/60 hover:border-indigo-500/30">
                    <div class="flex items-center gap-3 mb-2">
                        <span style="padding: 0.5rem; border-radius: 0.5rem; background-color: rgba(59, 130, 246, 0.1); color: #2563eb; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="4" width="18" height="4" rx="1" />
                                <rect x="3" y="10" width="18" height="4" rx="1" />
                                <rect x="3" y="16" width="18" height="4" rx="1" />
                                <circle cx="6" cy="6" r="0.75" fill="currentColor" />
                                <circle cx="6" cy="12" r="0.75" fill="currentColor" />
                                <circle cx="6" cy="18" r="0.75" fill="currentColor" />
                            </svg>
                        </span>
                        <h4 class="font-semibold text-gray-900 dark:text-white" style="margin: 0;">
                            {{ app()->getLocale() === 'ar' ? 'استهلاك السيرفر (Servers)' : 'Server Resources' }}
                        </h4>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400" style="margin: 0;">
                        {{ app()->getLocale() === 'ar' 
                            ? 'يعرض مدى استهلاك السيرفر من المعالج (CPU)، والذاكرة (Memory)، والمساحة التخزينية. من المهم متابعتها لتجنب توقف السيرفر المفاجئ.' 
                            : 'Shows server CPU, Memory, and storage utilization. Crucial to monitor to prevent sudden system downtime.' }}
                    </p>
                </div>

                <!-- Card 5: Slow Queries & Requests -->
                <div style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(4px); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(229, 231, 235, 0.8); transition: all 0.3s;" class="dark:bg-gray-800/40 dark:border-gray-700/60 hover:border-indigo-500/30">
                    <div class="flex items-center gap-3 mb-2">
                        <span style="padding: 0.5rem; border-radius: 0.5rem; background-color: rgba(245, 158, 11, 0.1); color: #d97706; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7c0-1.657 3.582-3 8-3s8 1.343 8 3M4 7c0 1.657 3.582 3 8 3s8-1.343 8-3M4 7v6c0 1.657 3.582 3 8 3s8-1.343 8-3V7M4 13v6c0 1.657 3.582 3 8 3s8-1.343 8-3v-6" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 16.5L21 21M18 13.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                            </svg>
                        </span>
                        <h4 class="font-semibold text-gray-900 dark:text-white" style="margin: 0;">
                            {{ app()->getLocale() === 'ar' ? 'الاستعلامات والطلبات البطيئة' : 'Slow Queries & Requests' }}
                        </h4>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400" style="margin: 0;">
                        {{ app()->getLocale() === 'ar' 
                            ? 'يكشف الصفحات واستعلامات قاعدة البيانات التي تستغرق وقتاً طويلاً للتحميل (أكثر من ثانية)، لتوجيه المطور لتحسين وتطوير كفاءتها.' 
                            : 'Identifies slow loading pages or database queries taking a long time, helping developers optimize app performance.' }}
                    </p>
                </div>

                <!-- Card 6: Exceptions -->
                <div style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(4px); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(229, 231, 235, 0.8); transition: all 0.3s;" class="dark:bg-gray-800/40 dark:border-gray-700/60 hover:border-indigo-500/30">
                    <div class="flex items-center gap-3 mb-2">
                        <span style="padding: 0.5rem; border-radius: 0.5rem; background-color: rgba(239, 68, 68, 0.1); color: #dc2626; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </span>
                        <h4 class="font-semibold text-gray-900 dark:text-white" style="margin: 0;">
                            {{ app()->getLocale() === 'ar' ? 'الأخطاء والاعتراضات (Exceptions)' : 'Application Errors' }}
                        </h4>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400" style="margin: 0;">
                        {{ app()->getLocale() === 'ar' 
                            ? 'يعرض أي أخطاء برمجية واجهت زوار الموقع أثناء التصفح بشكل مفصل لتسهيل تتبع المشاكل وإصلاحها وحماية تجربة المستخدم.' 
                            : 'Displays application errors and exceptions encountered by users, allowing developers to debug and fix bugs easily.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full" style="height: calc(100vh - 220px);">
        <iframe src="/pulse" class="w-full h-full border-0 rounded-xl shadow-sm" style="width: 100%; height: 100%; min-height: 750px;"></iframe>
    </div>
</x-filament-panels::page>
