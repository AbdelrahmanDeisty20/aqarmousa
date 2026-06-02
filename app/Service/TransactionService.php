<?php

namespace App\Service;

use App\Models\Transaction;

class TransactionService
{
    // إنشاء معاملة جديدة
    public function create(array $data): Transaction
    {
        // هنا ممكن نضيف أي logic إضافي قبل الحفظ
        // زي التحقق من رصيد المستخدم، أو تحديث حالة الوحدة
        return Transaction::create($data); // إنشاء المعاملة
    }

    // تحديث معاملة
    public function update(Transaction $transaction, array $data): Transaction
    {
        $transaction->update($data); // تحديث المعاملة
        return $transaction;
    }

    // حذف معاملة
    public function delete(Transaction $transaction): void
    {
        $transaction->delete(); // حذف المعاملة
    }

    public function getUserTransactions($user)
    {
        return Transaction::with(['unit.type', 'unit.city', 'unit.compound', 'unit.developer', 'unit.media'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
    }
}
