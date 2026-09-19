<div>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-book"></i>
                Alkitab
            </h3>

            <small class="text-muted">
                Firman Tuhan untuk direnungkan setiap hari
            </small>
        </div>

        <div>
            <span class="badge bg-primary">
                {{ strtoupper($version) }}
            </span>
        </div>

    </div>


    {{-- Control --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-3">

                {{-- Version --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Versi
                    </label>

                    <select
                        wire:model.live="version"
                        class="form-select"
                    >
                        <option value="tb">
                            Terjemahan Baru (TB)
                        </option>
                    </select>

                </div>


                {{-- Book --}}
                <div class="col-md-5">

                    <label class="form-label">
                        Kitab
                    </label>

                    <select
                        wire:model.live="book"
                        class="form-select"
                    >

                        <optgroup label="Perjanjian Lama">
                            <option value="Kejadian">Kejadian</option>
                            <option value="Keluaran">Keluaran</option>
                            <option value="Imamat">Imamat</option>
                            <option value="Bilangan">Bilangan</option>
                            <option value="Ulangan">Ulangan</option>
                            <option value="Yosua">Yosua</option>
                            <option value="Hakim-hakim">Hakim-hakim</option>
                            <option value="Rut">Rut</option>
                            <option value="1Samuel">1 Samuel</option>
                            <option value="2Samuel">2 Samuel</option>
                            <option value="1Raja-raja">1 Raja-raja</option>
                            <option value="2Raja-raja">2 Raja-raja</option>
                            <option value="Mazmur">Mazmur</option>
                            <option value="Amsal">Amsal</option>
                            <option value="Pengkhotbah">Pengkhotbah</option>
                            <option value="Yesaya">Yesaya</option>
                            <option value="Yeremia">Yeremia</option>
                            <option value="Yehezkiel">Yehezkiel</option>
                            <option value="Daniel">Daniel</option>
                        </optgroup>

                        <optgroup label="Perjanjian Baru">
                            <option value="Matius">Matius</option>
                            <option value="Markus">Markus</option>
                            <option value="Lukas">Lukas</option>
                            <option value="Yohanes">Yohanes</option>
                            <option value="Kisah Para Rasul">
                                Kisah Para Rasul
                            </option>
                            <option value="Roma">Roma</option>
                            <option value="1Korintus">1 Korintus</option>
                            <option value="2Korintus">2 Korintus</option>
                            <option value="Galatia">Galatia</option>
                            <option value="Efesus">Efesus</option>
                            <option value="Filipi">Filipi</option>
                            <option value="Kolose">Kolose</option>
                            <option value="1Petrus">1 Petrus</option>
                            <option value="2Petrus">2 Petrus</option>
                            <option value="1Yohanes">1 Yohanes</option>
                            <option value="2Yohanes">2 Yohanes</option>
                            <option value="3Yohanes">3 Yohanes</option>
                            <option value="Yudas">Yudas</option>
                            <option value="Wahyu">Wahyu</option>
                        </optgroup>

                    </select>

                </div>


                {{-- Chapter --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Pasal
                    </label>

                    <div class="input-group">

                        <button
                            wire:click="previousChapter"
                            class="btn btn-outline-secondary"
                            type="button"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </button>

                        <input
                            type="number"
                            wire:model.live="chapter"
                            wire:change="loadChapter"
                            min="1"
                            class="form-control text-center"
                        >

                        <button
                            wire:click="nextChapter"
                            class="btn btn-outline-secondary"
                            type="button"
                        >
                            <i class="bi bi-chevron-right"></i>
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Loading --}}
    <div
        wire:loading
        wire:target="loadChapter,book,version,previousChapter,nextChapter"
        class="text-center py-5"
    >
        <div class="spinner-border text-primary mb-3"></div>

        <div class="text-muted">
            Memuat firman Tuhan...
        </div>
    </div>


    {{-- Error --}}
    @if ($error)

        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle"></i>

            {{ $error }}

        </div>

    @endif


    {{-- Bible --}}
    @if (!$loading && count($verses))

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4 p-lg-5">

                <div class="text-center mb-4">

                    <h2 class="fw-bold mb-1">
                        {{ $book }} {{ $chapter }}
                    </h2>

                    <div class="text-muted">
                        Terjemahan Baru
                    </div>

                </div>


                <div class="bible-content">

                    @foreach ($verses as $verse)
                        @if ($verse['verse'] != 0)
                            @if ($verse['type'] === 'title')
                            
                            <h5 class="fw-bold mt-4 mb-3">
                                {{ $verse['content'] }}
                            </h5>
                            
                            @else
                            
                            <div class="mb-3">
                                
                                <span
                                    class="fw-bold text-primary me-2"
                                    >
                                    {{ $verse['verse'] }}
                                </span>
                                
                                <span class="lh-lg">
                                    {{ $verse['content'] }}
                                </span>
                                
                            </div>
                            
                            @endif
                        @endif

                    @endforeach

                </div>

            </div>

        </div>

    @endif


    {{-- Navigation --}}
    @if (!$loading && count($verses))

        <div class="d-flex justify-content-between mt-4">

            <button
                wire:click="previousChapter"
                class="btn btn-outline-primary"
                @disabled($chapter <= 1)
            >
                <i class="bi bi-chevron-left"></i>
                Pasal Sebelumnya
            </button>


            <button
                wire:click="nextChapter"
                class="btn btn-primary"
            >
                Pasal Berikutnya
                <i class="bi bi-chevron-right"></i>
            </button>

        </div>

    @endif

</div>