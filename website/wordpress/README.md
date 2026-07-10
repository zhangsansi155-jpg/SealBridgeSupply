# SealBridge Supply WordPress Site

这是 SealBridge Supply 企业官网的 WordPress 项目骨架，用于展示产品、应用场景、材料能力、合规说明和行业内容。

## 本地启动

1. 复制环境变量：

```bash
cp .env.example .env
```

2. 启动 WordPress：

```bash
docker compose up -d
```

3. 打开站点：

```text
http://localhost:8080
```

数据库管理入口：

```text
http://localhost:8081
```

## 初始设置建议

- 站点标题：SealBridge Supply
- 副标题：Custom Gasket Sourcing & Project Coordination Partner
- 后台主题：启用 `SealBridge`
- 固定链接：选择 `/%postname%/`
- 首页：使用静态页面，并选择首页模板
- 后台内容类型：主题内置 `Products` 和 `Applications`，用于维护产品和应用场景

## 推荐页面

- Home
- Products
- Applications
- Materials
- Capabilities
- Quality & Compliance
- About
- Contact / Request a Quote

## 内容方向

- 产品展示：Electrical Enclosure Gaskets、Control Cabinet Sealing Strips、EPDM Foam Gaskets、Silicone Gaskets、Adhesive Backed Die Cut Gaskets
- 应用场景：Outdoor Electrical Enclosures、Control Cabinets、Junction Boxes、EV Charger Cabinets、Solar Inverter Enclosures
- 内容文章：材料选择、IP 防护、RoHS/REACH、背胶工艺、模切和模压差异

## 建站后的第一批内容

建议先创建这些内容，让网站看起来完整：

- Products：6 个产品分类，每个分类放材料、工艺、应用、询价参数
- Applications：6 个应用场景，每个场景说明常用材料和密封要求
- Pages：About、Quality & Compliance、Contact / Request a Quote
- Posts：5 篇基础文章，用于 SEO 和客户教育
