# HotTextForum 项目文档结构

本项目采用集中化的文档管理策略，避免文档碎片化。

---

## 📂 文档目录

```
HotTextForum/
├── README.md                  # 项目主文档（快速开始、API、规则）
├── .claudeignore              # 开发规范与项目规则
└── DOCS/                      # 详细文档目录
    ├── UPGRADE.md             # PHP 8.5.7 升级详细报告
    ├── SECURITY.md            # 安全审计与防护机制
    └── MIGRATION.md           # 响应式模板迁移指南
```

---

## 📖 文档说明

### README.md
**项目主文档** - 适合快速了解项目

包含内容：
- 项目简介与核心特性
- 快速开始（安装、环境要求）
- 项目结构
- 安全特性概览
- 响应式设计说明
- JavaScript API 示例
- 开发规范与规则
- 升级历史摘要

**适合人群**：新用户、快速上手、API 查询

---

### DOCS/UPGRADE.md
**PHP 8.5.7 升级详细报告**

包含内容：
- 技术栈对比（2005 vs 2026）
- PHP 8.5.7 兼容性修复（102 文件）
- 核心文件重构详解
- 安全加固措施
- 响应式前端改造
- JavaScript 现代化
- 移动端优化
- 完整代码示例

**适合人群**：开发者、技术审查、迁移参考

**文件大小**：约 12 KB  
**内容深度**：⭐⭐⭐⭐⭐

---

### DOCS/SECURITY.md
**安全审计与防护机制**

包含内容：
- 安全评分详解（2.5/10 → 9.0/10）
- Session 安全实现
- CSRF 防护机制
- 速率限制配置
- XSS 防护
- 密码加密（Argon2id）
- 安全测试方法
- 漏洞修复记录

**适合人群**：安全审计、渗透测试、运维人员

**文件大小**：约 20 KB  
**内容深度**：⭐⭐⭐⭐⭐

---

### DOCS/MIGRATION.md
**响应式模板迁移指南**

包含内容：
- 快速开始（最小化改动 vs 完整迁移）
- 迁移步骤（4 步详解）
- 模板文件说明
- 安全集成方法
- JavaScript 功能使用
- CSS 响应式组件
- 浏览器兼容性
- 测试清单
- 常见问题

**适合人群**：前端开发、页面迁移、UI 改造

**文件大小**：约 11 KB  
**内容深度**：⭐⭐⭐⭐

---

## 🔧 开发规范

### 文档管理规则

1. **主文档原则**
   - `README.md` 保持简洁，作为项目入口
   - 详细内容放入 `DOCS/` 目录
   - 禁止在根目录创建多个临时文档

2. **更新原则**
   - 重大变更：更新 README.md + DOCS/ 相应文档
   - 小改动：仅更新 DOCS/ 相应文档
   - 使用 Git commit 记录详细变更历史

3. **禁止行为**
   - ❌ 每次改动就新建文档（如 `FINAL_*`, `*_COMPLETE`, `*_STATUS`）
   - ❌ 创建多个相似内容的文档
   - ❌ 在根目录堆积临时性质的文档

4. **推荐做法**
   - ✅ 直接更新现有文档
   - ✅ 使用 Git 追踪变更历史
   - ✅ 保持文档结构扁平（不超过 3 层）

---

## 📝 代码规范

### 安全规范（强制）

```php
// 1. 所有新页面必须引入安全模块
include_once './require/security_integration.php';

// 2. 添加速率限制（根据功能调整）
apply_rate_limit('action_name', 10, 60); // 10次/分钟

// 3. 用户输入过滤
$input = safeconvert($_POST['input']);

// 4. 输出转义
echo htmlspecialchars($output);
```

### 响应式设计规范

```php
// 使用响应式模板
require './header_responsive.php';

// 页面内容使用响应式组件
?>
<div class="htf-container">
    <div class="htf-row">
        <div class="htf-col htf-col-12 htf-col-md-6">左侧</div>
        <div class="htf-col htf-col-12 htf-col-md-6">右侧</div>
    </div>
</div>
<?php

require './footer_responsive.php';
```

---

## 🚀 快速索引

### 我想...

| 需求 | 查看文档 | 章节 |
|------|---------|------|
| 快速开始部署 | README.md | 快速开始 |
| 了解项目特性 | README.md | 核心特性 |
| 查看 API 用法 | README.md | JavaScript API |
| 了解升级详情 | DOCS/UPGRADE.md | 全文 |
| 审计安全性 | DOCS/SECURITY.md | 全文 |
| 迁移页面到响应式 | DOCS/MIGRATION.md | 迁移步骤 |
| 添加安全保护 | DOCS/MIGRATION.md | 安全集成 |
| 使用 JavaScript | README.md + DOCS/UPGRADE.md | JavaScript 章节 |
| 测试兼容性 | DOCS/MIGRATION.md | 测试清单 |
| 查看开发规范 | .claudeignore | 全文 |

---

## 📞 文档反馈

如发现文档问题或有改进建议：

1. 提交 GitHub Issue
2. 提交 Pull Request
3. 联系维护者

---

## 🔄 文档版本

| 版本 | 日期 | 变更 |
|------|------|------|
| 1.0 | 2026-06-17 | 文档结构重组，整合所有临时文档 |

---

**文档整合完成** - 保持简洁，避免碎片化  
维护者: Claude Code (Anthropic)
