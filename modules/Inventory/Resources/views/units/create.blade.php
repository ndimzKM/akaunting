<x-layouts.admin>
    <x-slot name="title">
        New Unit
    </x-slot>
    <x-slot name="content">
        <x-form.container>
            <x-form id="unit" route="inventory.units.store">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="Here you can enter the general information." />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        <div class="sm:col-span-6" x-data="{ values: [''] }">
                            <label class="form-control-label">Values</label>

                            <template x-for="(value, index) in values" :key="index">
                                <div class="flex items-center space-x-2 mb-2">
                                    <x-form.input.text
                                        name="values[]"
                                        x-bind:value="value"
                                        class="flex-1"
                                    />
                                    <button type="button"
                                            @click="values.splice(index, 1)"
                                            x-show="values.length > 1"
                                            class="text-red-500 hover:text-red-700 flex items-center">

                                        <x-icon icon="delete" class="h-3 w-3 text-gray-400" />
                                    </button>
                                </div>
                            </template>

                            <button type="button"
                                    @click="values.push('')"
                                    class="mt-2 text-sm text-blue-600 hover:text-blue-800 flex items-center"
                                    >
                                        <span>{{ trans('general.add') }}</span>
                            </button>
                        </div>
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="inventory.units.create" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>
    </x-slot>
</x-layouts.admin>
