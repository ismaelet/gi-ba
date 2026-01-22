<!DOCTYPE html>
<html lang="it">

<head>
	<meta charset="UTF-8" />
	<meta
		name="viewport"
		content="width=device-width, initial-scale=1.0"
	/>
	<title></title>

	<style>
		* {
			margin: 0;
		}

		h1,
		h2,
		h3 {
			margin-bottom: 16px;
			text-align: center;
			font-family: system-ui, 'Arial';
			font-weight: 400;
		}

		h1 {
			font-size: 36px;
			font-weight: 600;
		}

		h2 {
			font-size: 25px;
		}

		h3 {
			font-size: 18px;
		}

		p {
			color: #404040;
			font-family: system-ui, 'Arial';
			font-size: 16px;
			line-height: 1.8;
		}

		.contact-link {
			width: 1.5rem;
			aspect-ratio: 1;
		}
	</style>
</head>

<body
	style="margin: 0"
	bgcolor="#f4f5ff"
>
	<table
		width="100%"
		bgcolor="#f4f5ff"
	>
		<tr>
			<td style="padding: 32px">
				<center>
					<table style="width: 100%; max-width: 650px">
						<tr>
							<td>
								<table
									width="100%"
									style="padding: 48px 48px; border-radius: 32px;"
									bgcolor="#fff"
								>
									<tr>
										<td style="">
											<h1>
												{{ config('app.name') }}
											</h1>
										</td>
									</tr>
									<tr>
										<td style="">
											<p>
												{!! $text ?? ($slot ?? '') !!}
											</p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</center>
			</td>
		</tr>
	</table>
</body>

</html>
