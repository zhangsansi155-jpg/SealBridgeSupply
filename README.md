# SealBridge Supply

GitHub: [https://github.com/zhangsansi155-jpg/SealBridgeSupply](https://github.com/zhangsansi155-jpg/SealBridgeSupply)

SealBridge Supply 是一个外贸 SOHO 供应链服务品牌项目，聚焦：

- 电气箱密封垫
- 控制柜密封条
- EPDM 泡棉垫
- 硅胶垫片
- 背胶模切垫片
- 按图定制密封件

项目定位：

```text
Custom Gasket Sourcing & Project Coordination Partner
```

## 文档入口

- [项目总览](./PROJECT_BRIEF.md)
- [行业术语新人学习文档](./docs/enclosure-gasket-industry-glossary.md)

## 目录说明

- `docs/`：行业知识、术语、合规、培训资料
- `assets/`：后续放产品图片、样品图、车间图、视频素材
- `supplier-intake/`：供应商沟通话术、样品包清单、报价参数表
- `website/wordpress/`：WordPress 官网主题与 Docker 本地环境

## 本地启动官网

```bash
cd website/wordpress
chmod +x setup.sh
./setup.sh
```

启动后访问 `http://localhost:8080`，后台 `http://localhost:8080/wp-admin`（默认账号 `admin` / `sealbridge_admin`）。

## 近期行动

详见 [PROJECT_BRIEF.md](./PROJECT_BRIEF.md) 第 11 节，当前优先：

1. 确认并注册域名
2. 向工厂索取样品包（模板见 `supplier-intake/sample-pack-checklist.md`）
3. 整理报价参数（模板见 `supplier-intake/quote-parameter-template.md`）
4. 本地跑通官网并替换占位联系方式

