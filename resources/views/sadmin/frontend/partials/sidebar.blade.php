<ul class="settings-sidebar zList-three">
    <li>
        <a href="{{ route('super-admin.frontend.sections') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$activeFrontendSection }}">
            <span class="fs-15 fw-600 lh-22 text-black"><i class="fa-solid fa-layer-group me-2 text-para-text fs-14"></i>{{ __('Sections') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.frontend.features') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$activeFrontendFeatures }}">
            <span class="fs-15 fw-600 lh-22 text-black"><i class="fa-solid fa-star me-2 text-para-text fs-14"></i>{{ __('Features') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.frontend.services') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$activeFrontendServices }}">
            <span class="fs-15 fw-600 lh-22 text-black"><i class="fa-solid fa-briefcase me-2 text-para-text fs-14"></i>{{ __('Services') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.frontend.core-features') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$activeFrontendCore }}">
            <span class="fs-15 fw-600 lh-22 text-black"><i class="fa-solid fa-cubes me-2 text-para-text fs-14"></i>{{ __('Core Features') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.frontend.choose-us') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$activeFrontendChooseUs }}">
            <span class="fs-15 fw-600 lh-22 text-black"><i class="fa-solid fa-check-double me-2 text-para-text fs-14"></i>{{ __('Why Choose Us') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.frontend.faqs') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$activeFrontendFaqs }}">
            <span class="fs-15 fw-600 lh-22 text-black"><i class="fa-solid fa-circle-question me-2 text-para-text fs-14"></i>{{ __('FAQs') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.frontend.testimonials') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$activeFrontendTestimonials }}">
            <span class="fs-15 fw-600 lh-22 text-black"><i class="fa-solid fa-comments me-2 text-para-text fs-14"></i>{{ __('Testimonials') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.frontend.about') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$activeFrontendAbout }}">
            <span class="fs-15 fw-600 lh-22 text-black"><i class="fa-solid fa-circle-info me-2 text-para-text fs-14"></i>{{ __('About Page') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.frontend.policies') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$activeFrontendPolicies }}">
            <span class="fs-15 fw-600 lh-22 text-black"><i class="fa-solid fa-file-shield me-2 text-para-text fs-14"></i>{{ __('Policy Pages') }}</span>
            <div class="d-flex text-textBlack"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
</ul>
