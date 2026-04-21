@if(session('success'))
    <div style="margin-bottom:16px; padding:12px 16px; background:#d4edda; color:#155724; border-radius:8px; border:1px solid #c3e6cb;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
