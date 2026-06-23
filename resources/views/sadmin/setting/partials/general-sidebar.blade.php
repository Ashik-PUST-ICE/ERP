<ul class="settings-sidebar zList-three">
    <li>
        <a href="{{ route('super-admin.setting.application-settings') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$subApplicationSettingActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Application Setting') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.setting.storage.index') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$subStorageSettingActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Storage Setting') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.setting.logo-settings') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$subLogoSettingActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Logo Setting') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.setting.maintenance') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$subMaintenanceModeActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Maintenance Mode') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.setting.cache-settings') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$subCacheActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Cache Settings') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>

    <li>
        <a href="{{ route('super-admin.setting.configuration-settings') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$subConfigurationActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Configuration Settings') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>

    <li>
        <a href="{{ route('super-admin.setting.currencies.index') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$subCurrenciesActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Currency Settings') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>

    <li>
        <a href="{{ route('super-admin.setting.gateway.index') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$subGatewayActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Payment Gateway') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>

    <li>
        <a href="{{ route('super-admin.setting.languages.index') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$subLanguagesActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Multi Language') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.setting.email-template') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$subEmailActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Email Template') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>

    <li>
        <a href="{{ route('super-admin.setting.notify-template') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$subNotifyActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Notification Template') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
</ul>
