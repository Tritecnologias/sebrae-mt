<div class="row g-3">

    {{-- Nome --}}
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
        <input type="text" id="nome" name="nome"
               class="form-control @error('nome') is-invalid @enderror"
               value="{{ old('nome', $cliente->nome ?? '') }}" required>
        @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- E-mail --}}
    <div class="col-md-6">
        <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
        <input type="email" id="email" name="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $cliente->email ?? '') }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Telefone --}}
    <div class="col-md-4">
        <label for="telefone" class="form-label">Telefone <span class="text-danger">*</span></label>
        <input type="text" id="telefone" name="telefone"
               class="form-control @error('telefone') is-invalid @enderror"
               value="{{ old('telefone', $cliente->telefone ?? '') }}"
               placeholder="(00) 00000-0000" required>
        @error('telefone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- CEP --}}
    <div class="col-md-3">
        <label for="cep" class="form-label">CEP <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" id="cep" name="cep"
                   class="form-control @error('cep') is-invalid @enderror"
                   value="{{ old('cep', $cliente->cep ?? '') }}"
                   placeholder="00000-000" maxlength="9" required>
            <button type="button" class="btn btn-outline-secondary" id="btn-buscar-cep" title="Buscar CEP">
                <i class="bi bi-search"></i>
            </button>
            @error('cep') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div id="cep-feedback" class="form-text text-danger d-none"></div>
    </div>

    {{-- Rua --}}
    <div class="col-md-8">
        <label for="rua" class="form-label">Rua <span class="text-danger">*</span></label>
        <input type="text" id="rua" name="rua"
               class="form-control @error('rua') is-invalid @enderror"
               value="{{ old('rua', $cliente->rua ?? '') }}" required>
        @error('rua') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Bairro --}}
    <div class="col-md-4">
        <label for="bairro" class="form-label">Bairro <span class="text-danger">*</span></label>
        <input type="text" id="bairro" name="bairro"
               class="form-control @error('bairro') is-invalid @enderror"
               value="{{ old('bairro', $cliente->bairro ?? '') }}" required>
        @error('bairro') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Cidade --}}
    <div class="col-md-5">
        <label for="cidade" class="form-label">Cidade <span class="text-danger">*</span></label>
        <input type="text" id="cidade" name="cidade"
               class="form-control @error('cidade') is-invalid @enderror"
               value="{{ old('cidade', $cliente->cidade ?? '') }}" required>
        @error('cidade') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Estado --}}
    <div class="col-md-2">
        <label for="estado" class="form-label">UF <span class="text-danger">*</span></label>
        <input type="text" id="estado" name="estado"
               class="form-control @error('estado') is-invalid @enderror"
               value="{{ old('estado', $cliente->estado ?? '') }}"
               maxlength="2" style="text-transform:uppercase" required>
        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

</div>
