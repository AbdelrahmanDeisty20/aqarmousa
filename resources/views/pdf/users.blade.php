<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('admin.resources.users') }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background-color: #f2 f2 f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>{{ __('admin.resources.users') }}</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ __('admin.fields.name') }}</th>
                <th>{{ __('admin.fields.email') }}</th>
                <th>{{ __('admin.fields.role') }}</th>
                <th>{{ __('admin.fields.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
