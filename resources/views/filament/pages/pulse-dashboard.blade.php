<x-filament-panels::page>
    <div x-data="{ open: true }" class="mb-6">
        <!-- Banner Header -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-500/10 via-indigo-500/10 to-purple-500/10 border border-indigo-500/20 p-6 shadow-sm transition-all duration-300">
            <!-- Background decorative shapes -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/5 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-blue-500/5 rounded-full blur-2xl"></div>

            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-white/80 dark:bg-gray-800 rounded-xl shadow-sm border border-indigo-100 dark:border-gray-700 text-indigo-600 dark:text-indigo-400">
                        <svg class="animate-pulse" style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ app()->getLocale() === 'ar' ? 'دليل شاشة مراقبة أداء الموقع' : 'Site Performance Dashboard Guide' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ app()->getLocale() === 'ar' ? 'تعرف على كيفية قراءة مؤشرات الأداء الحية لموقعك والمحافظة على استقراره.' : 'Learn how to read real-time performance indicators and maintain site stability.' }}
                        </p>
                    </div>
                </div>

                <button @click="open = !open" class="flex items-center justify-center p-2 rounded-lg bg-white/50 hover:bg-white/80 dark:bg-gray-800/50 dark:hover:bg-gray-800 transition-colors text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 shadow-sm cursor-pointer">
                    <svg class="transition-transform duration-300" :class="{ 'rotate-180': !open }" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <!-- Expandable Content -->
            <div x-show="open" x-collapse x-transition class="mt-6 pt-6 border-t border-gray-200/50 dark:border-gray-700/50 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1: Cache -->
                <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-xl p-4 border border-gray-100 dark:border-gray-700/50 hover:border-indigo-500/30 transition-all duration-300">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="p-2 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </span>
                        <h4 class="font-semibold text-gray-900 dark:text-white">
                            {{ app()->getLocale() === 'ar' ? 'التخزين المؤقت (Cache)' : 'Cache Performance' }}
                        </h4>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400">
                        {{ app()->getLocale() === 'ar' 
                            ? 'يعرض سرعة استجابة الموقع. كلما زادت نسبة الـ (Hits) يعني أن الموقع سريع ويجلب البيانات من الذاكرة الفائقة دون استهلاك السيرفر وقاعدة البيانات.' 
                            : 'Displays how fast your site responds. A high Hit Rate means the site serves data from fast memory without putting load on the database.' }}
                    </p>
                </div>

                <!-- Card 2: Queues -->
                <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-xl p-4 border border-gray-100 dark:border-gray-700/50 hover:border-indigo-500/30 transition-all duration-300">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="p-2 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </span>
                        <h4 class="font-semibold text-gray-900 dark:text-white">
                            {{ app()->getLocale() === 'ar' ? 'طوابير العمل (Queues)' : 'Background Queues' }}
                        </h4>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400">
                        {{ app()->getLocale() === 'ar' 
                            ? 'لمراقبة المهام الخلفية كإرسال الإيميلات والإشعارات. تأكد دائماً أن قسم (Failed) لا يحتوي على أخطاء حمراء لضمان وصول الرسائل.' 
                            : 'Monitors background jobs like emails and notifications. Make sure the (Failed) section is empty to ensure message delivery.' }}
                    </p>
                </div>

                <!-- Card 3: Usage -->
                <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-xl p-4 border border-gray-100 dark:border-gray-700/50 hover:border-indigo-500/30 transition-all duration-300">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="p-2 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400">
                            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                        <h4 class="font-semibold text-gray-900 dark:text-white">
                            {{ app()->getLocale() === 'ar' ? 'نشاط المستخدمين (Usage)' : 'Application Usage' }}
                        </h4>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400">
                        {{ app()->getLocale() === 'ar' 
                            ? 'يكشف لك أكثر المستخدمين أو الحسابات نشاطاً وطلباً لصفحات الموقع خلال الساعة الماضية، مما يساعد على كشف أي هجمات أو ضغط غير طبيعي.' 
                            : 'Reveals the most active users or accounts requesting site pages over the last hour, helping spot any attacks or unusual load.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full" style="height: calc(100vh - 220px);">
        <iframe src="/pulse" class="w-full h-full border-0 rounded-xl shadow-sm" style="width: 100%; height: 100%; min-height: 750px;"></iframe>
    </div>
</x-filament-panels::page>
