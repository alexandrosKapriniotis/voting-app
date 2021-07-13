<nav class="hidden md:flex items-center justify-between text-gray-400 text-xs">
    <ul class="flex uppercase font-semibold border-b-4 pb-3 space-x-10">
        <li wire:click.prevent="setStatus('All')">
            <a href="#" class="border-b-4 pb-3
                    @if($status === 'All') border-blue text-gray-900 @endif">
                All Ideas (87)
            </a>
        </li>

        <li wire:click.prevent="setStatus('Considering')">
            <a href="#" class="pb-3 text-gray-400 transition duration-150 ease-in border-b-4 hover:border-blue
                                @if($status === 'Considering') border-blue text-gray-900 @endif">
                Considering (6)
            </a>
        </li>

        <li wire:click.prevent="setStatus('In progress')">
            <a href="#" class="pb-3 text-gray-400 transition duration-150 ease-in border-b-4 hover:border-blue @if($status === 'In progress') border-blue text-gray-900 @endif">
                In progress (1)
            </a>
        </li>

    </ul>

    <ul class="flex uppercase font-semibold border-b-4 pb-3 space-x-10">
        <li wire:click.prevent="setStatus('Implemented')">
            <a href="#" class="border-b-4 pb-3 @if($status === 'Implemented') border-blue text-gray-900 @endif">
                Implemented (10)
            </a>
        </li>

        <li wire:click.prevent="setStatus('Closed')">
            <a href="#" class="pb-3 text-gray-400 transition duration-150 ease-in border-b-4 hover:border-blue @if($status === 'Closed') border-blue text-gray-900 @endif">
                Closed (55)
            </a>
        </li>

    </ul>
</nav>
