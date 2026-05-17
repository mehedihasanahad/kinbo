# Form Requests & Validation

## When to Use
- Any form submission with more than two fields requires a dedicated `FormRequest` class.
- Place in `app/Http/Requests/`, mirroring controller namespace (e.g. `App\Http\Requests\StoreProductRequest`).

## Structure
```php
final class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Product::class);
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'images.*'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'category',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'price' => (int) ($this->price * 100), // convert to cents
        ]);
    }
}
```

## Rules
- `authorize()` returns `true` for guest-accessible routes; use `Gate` or `Policy` checks for protected ones.
- `rules()` must validate **every** expected input field — never leave unexpected fields unvalidated.
- Use `prepareForValidation()` to normalize input (trim, cast, transform) before rules run.
- Use `attributes()` to humanize field names in error messages.
- Use `messages()` to override specific error messages where defaults are unclear.
- File uploads: always validate `mimes`, `max` size, and dimensions where relevant.
- Unique rules on update: use `Rule::unique()->ignore($this->route('model'))`.
- Always type-hint the return of `rules()` as `array`.

## Accessing Validated Data
- Use `$request->validated()` exclusively — never `$request->all()` or `$request->input()` in store/update flows.
- Use `$request->safe()->only([...])` or `$request->safe()->except([...])` when you need a subset.
