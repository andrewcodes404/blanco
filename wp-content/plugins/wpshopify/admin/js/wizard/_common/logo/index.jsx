/** @jsx jsx */
import { jsx, css } from "@emotion/core"

function Logo() {
  const logoSVG = css`
    width: 35px;
    height: 35px;
    display: block;
  `
  const logoSVGPath = css`
    fill: #415aff;
  `
  return (
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" css={logoSVG}>
      <path
        d="M18.9 26.8c5.2 0 9.9 2.9 12.3 7.6l10 19.9s4.3-6.9 8.4-13.1l.8-1.2-5.7-12.5c-.2-.4.1-.8.5-.8h13c5.5 0 10.4 3.2 12.6 8.2l8.5 19.2 3.8-6.1c2.4-4 5.5-9.1 8.1-12.8l2.2-3.5C86.2 15 69.5 3.3 50.2 3.3c-17.4 0-32.6 9.5-40.7 23.5h9.4z"
        css={logoSVGPath}
      ></path>
      <path
        d="M94.6 35l-2.3 3.7h.1l-25 40.1c-.5.6-1.3.8-2 .4-.6-.4-.8-1.3-.4-1.9l4.5-7.3c-2.9.3-5.9-1-7.2-4L51.8 43 29 78.7c-.2.3-.7.4-1 .2l-1-.6c-.3-.2-.4-.7-.2-1l4.5-7.2c-2.8.3-5.8-1.1-7.1-4l-17-34.8c-2.6 5.8-4 12.2-4 19 0 26 21 47 47 47s47-21 47-47c0-5.4-.9-10.5-2.6-15.3z"
        css={logoSVGPath}
      ></path>
    </svg>
  )
}

export default Logo
