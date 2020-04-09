# live_online， echarts


简单的实时曲线展示，highchart用了，这次换echarts， 

服务端使用api接口，每五分钟上报一次数据（一天288个5分钟间隔）。

live_online 写入redis，hash存储，每天一个key。 一天288个时间点数据，即 一个hashname 存储288个key。 value值为在线数值。

同时，保存昨天的数据，写到文件存档（按月分目录，日期为文件名）。


使用方式：

服务端上报数据就好，请求方式，参数 在api里面。
