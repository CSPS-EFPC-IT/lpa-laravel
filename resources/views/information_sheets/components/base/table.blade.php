<div class="el-table el-table--border el-table--fit el-table--enable-row-hover el-table--enable-row-transition" style="width: 100%;">
    {{-- HEAD --}}
    <div class="el-table__header-wrapper">
        <table cellspacing="0" cellpadding="0" border="0" class="el-table__header" style="width: 100%;">
            @yield('colgroup')
            <thead>
                <tr>
                    @yield('thead')
                </tr>
            </thead>
        </table>
    </div>
    {{-- BODY --}}
    <div class="el-table__body-wrapper is-scrolling-none">
        <table cellspacing="0" cellpadding="0" border="0" class="el-table__body" style="width: 100%;">
            @yield('colgroup')
            <tbody>
                @yield('tbody')
            </tbody>
        </table>
    </div>
</div>
