<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // 出品中
    const STATE_SELLING = 'selling';
    // 購入済み
    const STATE_BOUGHT = 'bought';
    // castsフィールドを使うことで、カラムの値を取り出す際にデータ型を変換させることができる。連想配列のキー名にカラム名を、値に変換先のデータ型を指定する。
    protected $casts = [
        'bought_at' => 'datetime'
    ];


    public function secondaryCategory(){
        return $this->belongsTo(SecondaryCategory::class);
    }

    public function seller(){
        return $this->belongsTo(User::class,'seller_id');
    }

    public function condition(){
        return $this->belongsTo(ItemCondition::class,'item_condition_id');
    }

    // アクセサ。getXXXXXXXXAttributeという形で定義する（呼び出すときはXXXXXXだけで参照できる）
    public function getIsStateSellingAttribute(){
        return $this->state === self::STATE_SELLING;
    }

    public function getIsStateBoughtAttribute(){
        return $this->state === self::STATE_BOUGHT;
    }


}
