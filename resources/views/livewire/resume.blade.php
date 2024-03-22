<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <h5 class="card-header">
                    <span class="fas fa-fw fa-file-alt"></span>
                    {{ __('Resume') }}
                </h5>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-8 col-xl-4 mx-auto mx-xl-0 mb-3">
                            <livewire:resume.photo />
                        </div>

                        <div class="col-12 col-xl-8 mx-auto mx-xl-0 mb-3">
                            <livewire:resume.personal-information />
                        </div>

                        <div class="col">
                            <livewire:resume.skills />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
