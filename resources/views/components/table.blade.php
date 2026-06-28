@props([
    'headers' => [],
])

<div {{ $attributes->merge(['class' => 'table-card']) }}>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        <th>
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
