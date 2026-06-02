<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('admin.resources.units') }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background-color: #f2 f2 f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>{{ __('admin.resources.units') }}</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ __('admin.fields.title') }}</th>
                <th>{{ __('admin.fields.price') }}</th>
                <th>{{ __('admin.fields.area') }}</th>
                <th>{{ __('admin.fields.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($units as $unit)
            <tr>
                <td>{{ $unit->id }}</td>
                <td>{{ $unit->{'title_' . app()->getLocale()} ?? $unit->title_ar }}</td>
                <td>{{ number_format($unit->price) }} EGP</td>
                <td>{{ $unit->area }} m²</td>
                <td>{{ $unit->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
