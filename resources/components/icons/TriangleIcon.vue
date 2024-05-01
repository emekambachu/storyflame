<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    width="17"
    height="9"
    viewBox="0 0 17 9"
    fill="none"
  >
    <defs>
      <filter
        id="drop-shadow"
        x="-20%"
        y="-20%"
        width="140%"
        height="140%"
      >
        <feGaussianBlur
          in="SourceAlpha"
          stdDeviation="1"
        />
        <feOffset
          dx="0"
          dy="1"
          result="offsetblur"
        />
        <feFlood flood-color="#d0d0d0" />
        <feComposite
          in2="offsetblur"
          operator="in"
        />
        <feMerge>
          <feMergeNode />
          <feMergeNode in="SourceGraphic" />
        </feMerge>
      </filter>
    </defs>
    <path
      d="M14.5711 0.485289C15.462 0.485289 15.9081 1.56243 15.2782 2.1924L9.20711 8.26347C8.81658 8.654 8.18342 8.654 7.79289 8.26347L1.72183 2.1924C1.09187 1.56243 1.53803 0.485289 2.42894 0.485289L14.5711 0.485289Z"
      fill="currentColor"
      filter="url(#drop-shadow)"
    />
  </svg>
</template>
