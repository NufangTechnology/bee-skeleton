## [0.3.2] 2018-09-06 15:09:22 UTC

- [6e8755f](https://github.com/adhocore/php-cli/commit/6e8755f) docs: autocompletion (Jitendra Adhikari)
- [1152671](https://github.com/adhocore/php-cli/commit/1152671) chore(zsh.plugin): auto complete provider for zsh with oh-my-zsh (Jitendra Adhikari)

## [0.3.1] 2018-09-06 00:09:23 UTC

- [f390b6b](https://github.com/adhocore/php-cli/commit/f390b6b) refactor: remove redundant codeCoverageIgnore (Sushil Gupta)
- [cd8d109](https://github.com/adhocore/php-cli/commit/cd8d109) refactor: minor refactor on messages + add isWindows() method using DIRECTORY_SEPARATOR check to set pipes (Sushil Gupta)

## [0.3.0] 2018-09-04 15:09:50 UTC

- [a1c2c30](https://github.com/adhocore/php-cli/commit/a1c2c30) docs: add shell section and contributors (Jitendra Adhikari)
- [5146260](https://github.com/adhocore/php-cli/commit/5146260) test: shell tests (Jitendra Adhikari)
- [d1e8e73](https://github.com/adhocore/php-cli/commit/d1e8e73) refactor(shell): ignore cov, cleanup etc (Jitendra Adhikari)
- [8d5ebe9](https://github.com/adhocore/php-cli/commit/8d5ebe9) feat(shell): a shell wrapper (Jitendra Adhikari)
- [37c0e4c](https://github.com/adhocore/php-cli/commit/37c0e4c) Async true gives the process ID (Sushil Gupta)
- [1052ca0](https://github.com/adhocore/php-cli/commit/1052ca0) More style fixes (Sushil Gupta)
- [989213f](https://github.com/adhocore/php-cli/commit/989213f) Style fixes (Sushil Gupta)
- [29e8d13](https://github.com/adhocore/php-cli/commit/29e8d13) If timeout is set, and is set to wait (not async by default), then either wait until it runs or kill it after timeout occurs - if async (not wait) - then don't care about the process at all (Sushil Gupta)
- [7cf9536](https://github.com/adhocore/php-cli/commit/7cf9536) If not async, then check for timeout if it is still running and attempt to stop it (Sushil Gupta)
- [88bc092](https://github.com/adhocore/php-cli/commit/88bc092) Stop - not kill (Sushil Gupta)
- [40ba003](https://github.com/adhocore/php-cli/commit/40ba003) Minor formatting fixed (Sushil Gupta)
- [f81237e](https://github.com/adhocore/php-cli/commit/f81237e) Added set options method (Sushil Gupta)
- [2d44553](https://github.com/adhocore/php-cli/commit/2d44553) On destruct, if running, waiting until timeout and then attempting to stop, instead of directly attempting to stop (Sushil Gupta)
- [acabcca](https://github.com/adhocore/php-cli/commit/acabcca) Root namespace appended for microtime (Sushil Gupta)
- [e86580a](https://github.com/adhocore/php-cli/commit/e86580a) Removed redundant unblocking of getOutput (Sushil Gupta)
- [76fce1b](https://github.com/adhocore/php-cli/commit/76fce1b) Minor DocBlock update (Sushil Gupta)
- [926c4a6](https://github.com/adhocore/php-cli/commit/926c4a6) WIP - Implemented timeout checking and wait system - not working yet (Sushil Gupta)
- [641d229](https://github.com/adhocore/php-cli/commit/641d229) Minor refactor - removing updateProcessStatus when asking for getState - not related (Sushil Gupta)
- [205daed](https://github.com/adhocore/php-cli/commit/205daed) Refactored to add another state variable to store actual state of the shell execution vs the process status (Sushil Gupta)
- [62acd2d](https://github.com/adhocore/php-cli/commit/62acd2d) File default info added (Sushil Gupta)
- [acdbd64](https://github.com/adhocore/php-cli/commit/acdbd64) Refactor - assigning default null + only assigning exit value if not already set and process has stopped (Sushil Gupta)
- [d742ecb](https://github.com/adhocore/php-cli/commit/d742ecb) Updating status before sending back exitcodes (Sushil Gupta)
- [96e3a9e](https://github.com/adhocore/php-cli/commit/96e3a9e) Made private methods protected (Sushil Gupta)
- [3939825](https://github.com/adhocore/php-cli/commit/3939825) Setting exit code on proc_close from the proc_get_status itself (Sushil Gupta)
- [56ba25d](https://github.com/adhocore/php-cli/commit/56ba25d) Implemented suggestions from code-review (Sushil Gupta)
- [dbb3c21](https://github.com/adhocore/php-cli/commit/dbb3c21) Refactored small things (Sushil Gupta)
- [13380da](https://github.com/adhocore/php-cli/commit/13380da) Removed timeout - not used anywhere for now (Sushil Gupta)
- [2d0f815](https://github.com/adhocore/php-cli/commit/2d0f815) One more style fix (Sushil Gupta)
- [e93b398](https://github.com/adhocore/php-cli/commit/e93b398) More style fixes :/ (Sushil Gupta)
- [1be59a6](https://github.com/adhocore/php-cli/commit/1be59a6) More style fixes (Sushil Gupta)
- [e43a34f](https://github.com/adhocore/php-cli/commit/e43a34f) More style fixes (Sushil Gupta)
- [0874497](https://github.com/adhocore/php-cli/commit/0874497) Style fixes - unindenting inside <?php tag (Sushil Gupta)
- [5923304](https://github.com/adhocore/php-cli/commit/5923304) Removed vdd (Sushil Gupta)
- [304f148](https://github.com/adhocore/php-cli/commit/304f148) Removed wait method - wasn't working - to be added (Sushil Gupta)
- [735294c](https://github.com/adhocore/php-cli/commit/735294c) Removed env + cwd from the options, passing null, for the sprit of minimalism ;) (Sushil Gupta)
- [bfe1965](https://github.com/adhocore/php-cli/commit/bfe1965) Added basic test case for getOutput (Sushil Gupta)
- [4972de3](https://github.com/adhocore/php-cli/commit/4972de3) Moved to helper (Sushil Gupta)
- [425af5e](https://github.com/adhocore/php-cli/commit/425af5e) Added pipes for different platform, checking directory separator + added public method to return PID (Sushil Gupta)
- [19ce603](https://github.com/adhocore/php-cli/commit/19ce603) Minor refactoring (Sushil Gupta)
- [22a759a](https://github.com/adhocore/php-cli/commit/22a759a) Moved public functions to the bottom (Sushil Gupta)
- [87ed2e4](https://github.com/adhocore/php-cli/commit/87ed2e4) Made some methods private + added exitCode method (Sushil Gupta)
- [c4c4f4e](https://github.com/adhocore/php-cli/commit/c4c4f4e) Added wait and other methods (Sushil Gupta)
- [bd54feb](https://github.com/adhocore/php-cli/commit/bd54feb) Using constants for descriptors key (Sushil Gupta)
- [4d8578e](https://github.com/adhocore/php-cli/commit/4d8578e) Minor refactoring (Sushil Gupta)
- [1e42021](https://github.com/adhocore/php-cli/commit/1e42021) Shell wrapper - basic proc_open implemented (Sushil Gupta)

## [0.2.1] 2018-08-28 14:08:59 UTC

- [25c3f1a](https://github.com/adhocore/php-cli/commit/25c3f1a) docs: improve readability and organize (Jitendra Adhikari)

## [0.2.0] 2018-08-21 14:08:52 UTC

- [a75c76e](https://github.com/adhocore/php-cli/commit/a75c76e) feat(cmd.option): support multiline desc and indent them properly on help (Jitendra Adhikari)
- [7b04d18](https://github.com/adhocore/php-cli/commit/7b04d18) refactor: readme > README (Jitendra Adhikari)
- [6e79204](https://github.com/adhocore/php-cli/commit/6e79204) docs: exceptions preview (Jitendra Adhikari)
- [c5ffb12](https://github.com/adhocore/php-cli/commit/c5ffb12) test: 100% cov ftw (Jitendra Adhikari)
- [92f41ba](https://github.com/adhocore/php-cli/commit/92f41ba) feat(output.helper): add print trace (Jitendra Adhikari)
- [7b5080e](https://github.com/adhocore/php-cli/commit/7b5080e) refactor(app): output helper instantiation and print trace (Jitendra Adhikari)
