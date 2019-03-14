## Seaslog配置

```ini
[SeasLog]
extension = seaslog.so

;默认log根目录
seaslog.default_basepath = "/var/log/www"

; 日志格式模板 默认"%T | %L | %P | %Q | %t | %M"
; 进程ID | DateTime | Level | 当前内存使用峰值量 | Message 日志信息
seaslog.default_template = "%T | %L | %P | %U | %u | %M"

;是否启用buffer 1是 0否(默认)
seaslog.use_buffer = 1

;buffer中缓冲数量 默认0(不使用buffer_size)
seaslog.buffer_size = 10
```