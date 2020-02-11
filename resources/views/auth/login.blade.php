<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('base/navigation.app_name') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: "Segoe UI", "Arial", sans-serif;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            color: #524f74;
            background-color: #e4edf0;
        }

        .el-header {
            padding: 0;
        }

        .ie-detection {
            text-align: center;
            color: #ed6b2f;
            padding: 12px;
            margin: 20px 0;
        }

        .ie-detection svg {
            width: 60px;
            height: 60px;
        }

        .ie-detection p.hint {
            color: #524f74;
        }

        /*
         *  The following menu styling sections are inspired from styles found in the top-bar.vue.
         *  Since there are no router for the login page, we must make sure that the default-active
         *  attribute of the <el-menu> from element ui behaves the same way as if we had a router.
         *  Hence, few quirks were required: see li, .el-menu-item, .el-submenu__title
         *
        */
        .top-bar {
            user-select: none;
            padding: 0 20px;
            background-color: #201e2c;
            position: relative;
            /* make space for the side-bar-toggle button */
            padding-left: calc(64px + 15px);
            height: 100%;
            display: flex;
            align-items: center;
        }

        .top-bar .el-menu-item,
        .top-bar .el-submenu .el-submenu__title {
            height: 50px;
            line-height: 50px;
        }

        .top-bar .el-menu {
            display: flex;
            flex-direction: row;
        }

        .top-bar .el-menu > .el-menu-item {
            border: 0px;
            background-color: #201e2c;
        }

        .sub-menu .el-menu,
        .sub-menu .el-menu-item,
        .sub-menu .el-submenu .el-submenu__title {
            color: black !important;
            background-color: white !important;
        }

        .sub-menu .el-menu {
            border-radius: 0;
            border: 1px solid #cdcdcd;
            padding: 0;
            margin-top: 2px;
        }

        .sub-menu .el-menu-item:hover {
            color: white !important;
            background-color: #322f43 !important;
        }

        li:hover,
        li:hover .el-submenu__title {
            background-color: #322f43 !important;
            cursor: pointer;
        }

        .el-submenu__title {
            border: none !important;
        }

        .sub-menu .el-menu-item a {
            display: block;
            width: 100%;
            padding: 0 10px;
            margin: 0 -10px;
            color: black !important;
            background-color: transparent !important;
            text-decoration: none;
            font-weight: normal;
        }

        .sub-menu .el-menu-item a:hover {
            color: white !important;
            cursor: pointer;
        }

        h1 {
            margin: 0;
            color: #fff;
            display: flex;
            width: 100%;
            font-weight: 600;
        }

        .lang {
            transition: all 0.3s;
            display: flex;
            justify-content: flex-end;
            height: 100%;
            align-items: center;
            padding: 0 20px;
            box-sizing: border-box;
            color: #fff;
            text-decoration: none;
            font-weight: normal;
        }

        .lang:hover {
            transition: all 0.3s;
            color: #fff;
            background-color: #322f43;
        }

        .login-wrap {
            width: 600px;
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -40%);
            background-color: #fff;
            padding: 20px 40px;
            border-radius: 4px;
            border: 1px solid #ececec;
        }

        .controls-wrap .el-form-item__content {
            margin: 0 !important;
            text-align: right;
        }

        .el-icon-view:hover, .el-icon-view.active {
            cursor: pointer;
            color: #524f74;
        }
        .cred-warning {
            text-align: center;
        }
        .cred-warning i {
            color: #ed6b2f;
        }
    </style>
</head>
<body>
    <div id="app" v-cloak>
        <el-container>
            <el-header height="50px">
                <div class="top-bar">
                    <h1>{{ __('base/navigation.app_name') }}</h1>

                    <el-menu
                        background-color="#201e2c"
                        text-color="#fff"
                        active-text-color="#fff"
                        class="top-menu"
                        mode="horizontal"
                    >
                        <el-submenu index="help-menu" popper-class="sub-menu">
                            <template slot="title">{{ __('base/navigation.help') }}</template>
                            <el-menu-item index=""><a href="{{ __('base/navigation.help_support_centre_url') }}" target="_blank">{{ __('base/navigation.help_support_centre') }}</a></el-menu-item>
                            <el-menu-item index=""><a href="{{ __('base/navigation.help_getting_started_url') }}" target="_blank">{{ __('base/navigation.help_getting_started') }}</a></el-menu-item>
                            <el-menu-item index=""><a href="{{ __('base/navigation.help_projects_url') }}" target="_blank">{{ __('base/navigation.help_projects') }}</a></el-menu-item>
                            <el-menu-item index=""><a href="{{ __('base/navigation.help_learning_products_url') }}" target="_blank">{{ __('base/navigation.help_learning_products') }}</a></el-menu-item>
                        </el-submenu>
                        <el-menu-item index="language"><a :href="'/' + toggledLocale + '/login'" class="lang">{{ __('base/navigation.language_toggle') }}</a></el-menu-item>
                    </el-menu>

                </div>
            </el-header>
            <el-main>
                <div class="login-wrap">
                    <transition name="fade" mode="out-in">
                        <div class="ie-detection" v-if="showWarning" key="warning">
                            <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="internet-explorer" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-internet-explorer fa-w-16 fa-3x"><path fill="currentColor" d="M483.049 159.706c10.855-24.575 21.424-60.438 21.424-87.871 0-72.722-79.641-98.371-209.673-38.577-107.632-7.181-211.221 73.67-237.098 186.457 30.852-34.862 78.271-82.298 121.977-101.158C125.404 166.85 79.128 228.002 43.992 291.725 23.246 329.651 0 390.94 0 436.747c0 98.575 92.854 86.5 180.251 42.006 31.423 15.43 66.559 15.573 101.695 15.573 97.124 0 184.249-54.294 216.814-146.022H377.927c-52.509 88.593-196.819 52.996-196.819-47.436H509.9c6.407-43.581-1.655-95.715-26.851-141.162zM64.559 346.877c17.711 51.15 53.703 95.871 100.266 123.304-88.741 48.94-173.267 29.096-100.266-123.304zm115.977-108.873c2-55.151 50.276-94.871 103.98-94.871 53.418 0 101.981 39.72 103.981 94.871H180.536zm184.536-187.6c21.425-10.287 48.563-22.003 72.558-22.003 31.422 0 54.274 21.717 54.274 53.722 0 20.003-7.427 49.007-14.569 67.867-26.28-42.292-65.986-81.584-112.263-99.586z" class=""></path></svg>
                            <p><strong>{{ __('pages/login.ie_detection_notice') }}</strong></p>
                            <p class="hint">{{ __('pages/login.ie_detection_hint') }}</p>
                            <p><el-button type="primary" @click="acceptToUseIE = true">{{ __('pages/login.ie_detection_continue') }}</el-button></p>
                        </div>

                        <div v-else key="login">
                            <h2>{{ __('pages/login.header') }}</h2>
                            <p class="cred-warning">
                                <i class="el-icon-warning"></i>
                                {{ __('pages/login.machine_credentials_msg') }}
                            </p>
                            <el-form
                                ref="form"
                                label-width="30%"
                                method="POST"
                                action="{{ route('login') }}">
                                {{ csrf_field() }}
                                <el-form-item label="{{ __('entities/user.username') }}" for="username" :class="['is-required', {'is-error': verrors.collect('username').length }]" prop="username">
                                    <el-input id="username" name="username" v-model="username" v-validate="'required'" data-vv-as="{{ __('entities/user.username') }}" @keyup.native.enter="onSubmit" autofocus></el-input>
                                    <form-error name="username"></form-error>
                                </el-form-item>
                                <el-form-item label="{{ __('entities/user.password') }}" for="password" :class="['is-required', {'is-error': verrors.collect('password').length }]" prop="password">
                                    <el-input id="password" name="password" :type="isPasswordVisible ? 'text' : 'password'" v-model="password" v-validate="'required'" data-vv-as="{{ __('entities/user.password') }}" @keyup.native.enter="onSubmit">
                                        <i
                                            class="el-icon-view el-input__icon"
                                            slot="suffix"
                                            @mousedown="isPasswordVisible = true"
                                            @mouseup="isPasswordVisible = false">
                                        </i>
                                    </el-input>
                                    <form-error name="password"></form-error>
                                </el-form-item>
                                <el-form-item for="remember">
                                    <el-checkbox name="remember" name="remember" v-model="remember" @keyup.native.enter="onSubmit" label="{{ __('pages/login.remember') }}"></el-checkbox>
                                </el-form-item>
                                <el-form-item class="controls-wrap">
                                    <el-button type="primary" :loading="isSubmitting" @click="onSubmit">{{ __('pages/login.login') }}</el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                    </transition>
                </div>
            </el-main>
        </el-container>
    </div>
    <div class="loading-spinner">
        <svg viewBox="25 25 50 50" class="circular"><circle cx="50" cy="50" r="20" fill="none" class="path"></circle></svg>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/login.js') }}"></script>
</body>
</html>
