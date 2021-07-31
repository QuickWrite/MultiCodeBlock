# How to contribute to MultiCodeBlock
First off, thanks for taking the time to contribute!

#### How to install MultiCodeBlock
1. Clone the repository through Git
2. Put it inside the extensions folder in your own MediaWiki installation
3. Install the necessary dependencies through `composer install` in the `includes/` folder.

#### **Did you find a bug?**

* **Do not open up a GitHub issue if the bug is an issue
  in PHP or MediaWiki**, and instead open an issue in one of their respective bug trackers.
* **Ensure the bug was not already reported** by searching on GitHub under [Issues](https://github.com/QuickWrite/MultiCodeBlock/issues).
* If you're unable to find an open issue addressing the problem, [open a new one](https://github.com/QuickWrite/MultiCodeBlock/issues/new). Be sure to include a **title and clear description**, as much relevant information as possible, and a **code sample** or an **executable test case** demonstrating the expected behavior that is not occurring.
* If possible, use the relevant bug report templates to create the issue.

> **Note:** If you find a **Closed** issue that seems like it is the same thing that you're experiencing, open a new issue and include a link to the original issue in the body of your new one.

#### How Do I Submit A (Good) Bug Report?

Bugs are tracked as [GitHub issues](https://guides.github.com/features/issues/). After you've determined [which repository](#atom-and-packages) your bug is related to, create an issue on that repository and provide the following information by filling in [the template](https://github.com/atom/.github/blob/master/.github/ISSUE_TEMPLATE/bug_report.md).

Explain the problem and include additional details to help maintainers reproduce the problem:

* **Use a clear and descriptive title** for the issue to identify the problem.
* **Describe the exact steps which reproduce the problem** in as many details as possible. When listing steps, **don't just say what you did, but explain how you did it**.
* **Provide specific examples to demonstrate the steps**. Include links to files or GitHub projects, or copy/pasteable snippets, which you use in those examples. If you're providing snippets in the issue, use [Markdown code blocks](https://help.github.com/articles/markdown-basics/#multiple-lines).
* **Describe the behavior you observed after following the steps** and point out what exactly is the problem with that behavior.
* **Explain which behavior you expected to see instead and why.**
* **Include screenshots and animated GIFs** which show you following the described steps and clearly demonstrate the problem.
* **If you're reporting that MediaWiki crashed because of MultiCodeBlock**, include a crash report with a stack trace from the operating system. Include the crash report in the issue in a [code block](https://help.github.com/articles/markdown-basics/#multiple-lines), a [file attachment](https://help.github.com/articles/file-attachments-on-issues-and-pull-requests/), or put it in a [gist](https://gist.github.com/) and provide link to that gist.
* **If the problem wasn't triggered by a specific action**, describe what you were doing before the problem happened and share more information using the guidelines below.

#### **Did you write a patch that fixes a bug?**

* Open a new GitHub pull request with the patch.
* Ensure the PR description clearly describes the problem and solution. Include the relevant issue number if applicable.

#### **Suggesting Enhancements**
This section guides you through submitting an enhancement suggestion for Atom, including completely new features and minor improvements to existing functionality. Following these guidelines helps maintainers and the community understand your suggestion 📝 and find related suggestions 🔎.
You should suggest your idea as a [GitHub Issue](https://github.com/QuickWrite/MultiCodeBlock/issues/new).

* **Use a clear and descriptive title** for the issue to identify the suggestion.
* **Provide a step-by-step description of the suggested enhancement** in as many details as possible.
* **Provide specific examples to demonstrate the steps**. Include copy/pasteable snippets which you use in those examples, as [Markdown code blocks](https://help.github.com/articles/markdown-basics/#multiple-lines).
* **Describe the current behavior** and **explain which behavior you expected to see instead** and why.
* **Include screenshots and animated GIFs** which help you demonstrate the steps or point out the part of MultiCodeBlock which the suggestion is related to.
* **Explain why this enhancement would be useful** to most users and isn't something that can or should be implemented as a different extension.
* **Specify which version of MultiCodeBlock you're using.**
* **Specify the name and version of the OS you're using.**

#### Styleguide
If you are planning to commit any changes to the project, it would be highly appreciated if you were to follow the project code style conventions.

All of the code must be indented by tabs and curly brackets must be set in the compact style.

PHP:
```php
/**
 * Functions should be documented in the PHPDocumentor standard.
 */
function example() {
    // Strings should always be '', except when they should have extra functions inside them.
    echo 'This is an example!';
}
```

CSS:
```css
/* There are no real comment guides */
style {
    /* Even though it is possible to leave the last seimcolon out, it shouldn't be done */
    display: block;
}
```

JavaScript:
```javascript
// Variables should always be let or const
let counter = 0;

// Semicolons must be set after each statement.
document.getElementById('myid').addEventListener('click', () => {
    console.log(counter);

    counter++;
});
```

#### Thanks!
Thanks for reading the *CONTRIBUTING* file and helping the project!