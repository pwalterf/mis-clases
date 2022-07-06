<template>
    <ol class="flex items-center whitespace-nowrap min-w-0" aria-label="Breadcrumb">
        <slot name="links" />

        <li class="font-semibold text-lg md:text-xl leading-tight text-purple-700 truncate" aria-current="page">
            <slot name="static" />
        </li>
    </ol>
</template>