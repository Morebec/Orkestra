# Release Process
This section explains the strategy, and the process used to develop, release and maintain the different 
versions of Orkestra.

Given that Orkestra is being used in production by different systems, it is imperative to have a sensible
process in place to avoid breaking production systems when releasing new versions of the framework.

Orkestra release follows the [Semantic Versioning](https://semver.org/) strategy:
- **Patch Version** (`x.y.1`, `x.y.2`): Contains bug fixes. Upgrading from one patch version to the other should always only 
concern bug and security fixes. Meaning, applications should always be able to upgrade from one patch version to the other, 
without breaking changes.

- **Minor Version** (`x.1.z`, `x.2.z`): Contains bug fixes (as part of patches or directly) and *new features* without introducing breaking changes
allowing applications to upgrade safely from one minor version to the other, without requiring any upgrade procedure.

- **Major Version** (`1.y.z`, `2.y.z`): Contains bug fixes, new features and **breaking changes**. An application wanting to upgrade to a new major version
will usually be required to make some changes in order to be compatible with this new major version.

## Source Control (Git)
Orkestra's development is done using Source Control with Git and Github.
Being a library it uses a specific branching model differing from the standard application branching model.


## Branches
For every major version, there should always be a dedicated branch (E.g: `1.x`, `2.x`).
It follows the following naming convention: `major.x`.
Depending on the development effort some branches should be used:
- **New features** (Non Breaking Change): This can safely be done by basing a feature branch on the latest `major.x` branch.  
- **New features** (Breaking Change):  This should be done in a new feature branch basing itself from a new `major.x + 1` branch. These typed of features should always be a coordinated effort
to ensure that the Breaking Changes are actually required. We always prefer to deprecate code and support them until the next major version.
- **Bug Fixes**: Should always be fixed in the oldest major version supported and brought back to all newer releases.
- **Security Fixes**: Should always be fixed in the oldest major version supported and brought back to all newer releases.

## Tags
Tags mark points in the commit history as being stable versions.

Tags for stable releases have the following naming convention:
`vmajor.minor.patch`. 
E.g.: `v1.0.4`

If a version is a preview such as an `alpha` or `beta` it should always
follow this naming convention: `vmajor.minor.patch-alpha` or `vmajor.minor.patch-beta` 
E.g.: `v1.0.4-alpha`.