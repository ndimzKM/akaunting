<x-layouts.admin>
    <x-slot name="title">
        {{ trans('inventory::general.units.name') }}
    </x-slot>
    <x-slot name="content">
        <h1>Units</h1>

        <p>Manage units</p>
    </x-slot>
    <x-slot name="buttons">
        @can('create-inventory-main')
            <x-link href="{{ route('inventory.units.create') }}" kind="primary" id="index-more-actions-new-item">
                {{ trans('general.title.new', ['type' =>
                trans_choice('general.inventory', 1)]) }}
            </x-link>
        @endcan
    </x-slot>
    <x-slot name="content">
        @if ($units->count())
            <x-index.container>
                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th kind="bulkaction">
                                <x-index.bulkaction.all />
                            </x-table.th>

                            <x-table.th class="w-6/12">
                                <x-sortablelink column="name" title="{{ trans('general.name') }}" />
                            </x-table.th>

                            <x-table.th class="w-6/12">
                                Values
                            </x-table.th>

                            <x-table.th kind="action">
                                {{ trans('general.actions') }}
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($units as $item)
                            <x-table.tr href="{{ route('inventory.units.edit', $item->id) }}">
                                <x-table.td kind="bulkaction">
                                    <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->name }}" />
                                </x-table.td>

                                <x-table.td class="w-6/12">
                                    <div class="font-bold">
                                        {{ $item->name }}
                                    </div>
                                </x-table.td>

                                <x-table.td class="w-6/12">
                                    <div class="flex flex-wrap gap-1">
                                        @php
                                            $values = json_decode($item->values, true) ?? [];
                                        @endphp
                                        @foreach($values as $value)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-sm font-medium bg-gray-100 text-gray-800">
                                                {{ $value }}
                                            </span>
                                        @endforeach
                                    </div>
                                </x-table.td>

                                <x-table.td kind="action">
                                    <x-table.actions :model="$item" />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>
            </x-index.container>
        @else
            <x-empty-page group="inventory" page="inventory.units" />
        @endif
    </x-slot>
</x-layouts.admin>
