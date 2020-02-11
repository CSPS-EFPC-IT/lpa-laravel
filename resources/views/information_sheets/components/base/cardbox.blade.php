<div class="el-card">
    @if (isset($title))
    <div class="el-card__header">
        <h2>
            <a href="#" class="toggle"><i class="el-icon-check"></i></a>
            {{ $title }}
        </h2>
    </div>
    @endif
    <div class="el-card__body">
        {{ $slot }}
    </div>
</div>
