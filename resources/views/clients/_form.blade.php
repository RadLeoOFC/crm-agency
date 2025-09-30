<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $client->name ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="contact_person">Contact person</label>
            <input type="contact_person" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person"
                value="{{ old('contact_person', $client->contact_person ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{ old('email', $client->email ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                value="{{ old('phone', $client->phone ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="company">Company</label>
            <input type="company" class="form-control @error('company') is-invalid @enderror" id="company" name="company"
                value="{{ old('company', $client->company ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="vat_number">Vat number</label>
            <input type="vat_number" class="form-control @error('vat_number') is-invalid @enderror" id="vat_number" name="vat_number"
                value="{{ old('vat_number', $client->vat_number ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="country">Country</label>
            <input type="country" class="form-control @error('country') is-invalid @enderror" id="country" name="country"
                value="{{ old('country', $client->country ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="city">City/Town/Village</label>
            <input type="city" class="form-control @error('phone') is-invalid @enderror" id="city" name="city"
                value="{{ old('city', $client->city ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="address" class="form-control @error('address') is-invalid @enderror" id="address" name="address"
                value="{{ old('address', $client->address ?? '') }}" required>
        </div>
        
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" 
                    class="custom-control-input" 
                    id="is_active" 
                    name="is_active" 
                    value="1"
                    {{ old('is_active', $client->is_active ?? true) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_active">Active</label>
            </div>
            @error('is_active')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<x-datepicker-script />
