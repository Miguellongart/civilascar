@props(['slot' => 'auto', 'format' => 'auto', 'responsive' => true, 'style' => ''])

@if(config('services.google.adsense_id'))
    <div class="adsense-container" style="{{ $style }}">
        <ins class="adsbygoogle"
             style="display:block{{ $responsive ? '' : ';width:100%;height:100%' }}"
             data-ad-client="{{ config('services.google.adsense_id') }}"
             data-ad-slot="{{ $slot }}"
             data-ad-format="{{ $format }}"
             {{ $responsive ? 'data-full-width-responsive="true"' : '' }}>
        </ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
@else
    {{-- Fallback: mostrar banner placeholder si no hay AdSense configurado --}}
    <div class="banner-placeholder" style="background-color: #f5f5f5; border: 2px dashed #ddd; padding: 20px; text-align: center; border-radius: 8px; {{ $style }}">
        <p class="text-muted mb-0">
            <i class="bi bi-image"></i> Espacio publicitario
            <br>
            <small>Configure GOOGLE_ADSENSE_ID en .env para activar anuncios</small>
        </p>
    </div>
@endif
