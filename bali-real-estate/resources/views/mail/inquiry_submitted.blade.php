@component('mail::message')
# 新規お問い合わせ

**物件**：{{ $property->title ?? '-' }}  
**氏名**：{{ $inquiry->name }}  
**メール**：{{ $inquiry->email }}  
**電話**：{{ $inquiry->phone ?? '-' }}

---

**メッセージ**

@isset($inquiry->message)
{{ $inquiry->message }}
@else
（メッセージは入力されていません）
@endisset

@component('mail::button', ['url' => url('/admin/inquiries')])
管理画面で確認
@endcomponent

@endcomponent
