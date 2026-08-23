<?php
/**
 * Auto Diagnostic & Storage Link Repair Script for Aqarmousa
 */

header('Content-Type: text/html; charset=utf-8');

echo "<h2>🔧 أداة فحص وإصلاح ملفات التخزين (Storage Diagnostic & Fix Tool)</h2>";
echo "<style>body{font-family:sans-serif;direction:rtl;padding:20px;background:#f9fafb;} .ok{color:green;font-weight:bold;} .err{color:red;font-weight:bold;} .box{background:#fff;padding:15px;border-radius:8px;border:1px solid #ddd;margin-bottom:15px;}</style>";

$targetDir = realpath(__DIR__ . '/../storage/app/public');
$linkPath = __DIR__ . '/storage';

echo "<div class='box'>";
echo "<h3>1. فحص مسارات التخزين:</h3>";
echo "مسار مجلد التخزين الأصلي: <code>" . htmlspecialchars($targetDir ?: 'غير موجود!') . "</code><br>";
echo "مسار الرابط في الـ public: <code>" . htmlspecialchars($linkPath) . "</code><br>";

if (!$targetDir || !is_dir($targetDir)) {
    echo "<p class='err'>❌ مجلد storage/app/public غير موجود على السيرفر!</p>";
} else {
    echo "<p class='ok'>✅ مجلد storage/app/public موجود على السيرفر.</p>";
}
echo "</div>";

echo "<div class='box'>";
echo "<h3>2. فحص ملفات الإعدادات (Settings Images):</h3>";
$settingsDir = $targetDir . '/settings';
if (is_dir($settingsDir)) {
    $files = array_diff(scandir($settingsDir), array('.', '..'));
    echo "عدد الصور الموجودة في مجلد settings: <b>" . count($files) . "</b><br>";
    if (count($files) > 0) {
        echo "<ul>";
        foreach ($files as $file) {
            echo "<li><code>" . htmlspecialchars($file) . "</code></li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='err'>⚠️ مجلد storage/app/public/settings فارغ! يرجى رفع ملفات الصور إليه من جهازك المحمول/اللوكال.</p>";
    }
} else {
    echo "<p class='err'>❌ المجلد storage/app/public/settings غير موجود على السيرفر!</p>";
    @mkdir($settingsDir, 0755, true);
    echo "<p class='ok'>⚡ تم إنشاء المجلد storage/app/public/settings تلقائياً.</p>";
}
echo "</div>";

echo "<div class='box'>";
echo "<h3>3. فحص وإصلاح رابط الـ Symlink (public/storage):</h3>";

if (file_exists($linkPath)) {
    if (is_link($linkPath)) {
        $linkTarget = readlink($linkPath);
        echo "الرابط الحالي يشير إلى: <code>" . htmlspecialchars($linkTarget) . "</code><br>";
        
        if (!file_exists($linkTarget) || strpos($linkTarget, 'C:') !== false) {
            echo "<p class='err'>⚠️ الرابط الحالي مكسور أو يشير إلى مسار الجهاز اللوكال! جاري حذفه وتصحيحه...</p>";
            @unlink($linkPath);
        } else {
            echo "<p class='ok'>✅ الـ Symlink يعمل ويشير إلى مسار صحيح.</p>";
        }
    } else if (is_dir($linkPath)) {
        echo "<p class='err'>⚠️ يوجد مجلد حقيقي باسم public/storage بدلاً من Symlink.</p>";
    }
}

if (!file_exists($linkPath) && $targetDir) {
    if (function_exists('symlink')) {
        $res = @symlink($targetDir, $linkPath);
        if ($res) {
            echo "<p class='ok'>🎉 تم إنشاء الـ Symlink بنجاح!</p>";
        } else {
            echo "<p class='err'>❌ فشل إنشاء الـ Symlink تلقائياً! جاري نسخ الصور مباشرة كحل بديل...</p>";
            copyDir($targetDir, $linkPath);
        }
    } else {
        echo "<p class='err'>⚠️ دالة symlink معطلة على السيرفر. جاري نسخ الصور لمجلد public/storage تلقائياً...</p>";
        copyDir($targetDir, $linkPath);
    }
}
echo "</div>";

function copyDir($src, $dst) {
    @mkdir($dst, 0755, true);
    $dir = opendir($src);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyDir($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
    echo "<p class='ok'>✅ تم نسخ الملفات إلى public/storage بنجاح!</p>";
}

echo "<div class='box' style='background:#e0f2fe;'>";
echo "<h3>✨ النتيجة:</h3>";
echo "قم بزيارة هذا الرابط لاختبار فتح صورة اللوجو مباشرة:<br>";
$host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
echo "<a href='" . $host . "/storage/settings/01M0Q1PPTZ580N68R2CMXYFS23.png' target='_blank'>" . $host . "/storage/settings/01M0Q1PPTZ580N68R2CMXYFS23.png</a>";
echo "</div>";
