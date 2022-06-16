<template>
    <ol class="flex items-center whitespace-nowrap min-w-0" aria-label="Breadcrumb">
        <slot name="links" />

        <li class="font-semibold text-lg md:text-xl leading-tight text-gray-800 truncate dark:text-gray-200" aria-current="page">
            <slot name="static" />
        </li>
    </ol>
</template>